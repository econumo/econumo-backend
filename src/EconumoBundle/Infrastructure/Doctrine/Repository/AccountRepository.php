<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Repository;

use App\EconumoBundle\Domain\Entity\Account;
use App\EconumoBundle\Domain\Entity\AccountAccess;
use App\EconumoBundle\Domain\Entity\User;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Exception\NotFoundException;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    /**
     * @inheritDoc
     */
    public function save(array $accounts): void
    {
        try {
            foreach ($accounts as $account) {
                $this->getEntityManager()->persist($account);
            }

            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAvailableForUserId(Id $userId): array
    {
        $builder = $this->createQueryBuilder('a');
        return $builder
            ->select('a')
            ->leftJoin(AccountAccess::class, 'aa', Join::WITH, 'aa.account = a')
            ->where($builder->expr()->orX(
                $builder->expr()->eq('a.user', ':user'),
                $builder->expr()->eq('aa.user', ':user'),
            ))
            ->andWhere('a.isDeleted = false')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getUserAccounts(Id $userId): array
    {
        $builder = $this->createQueryBuilder('a');
        return $builder
            ->select('a')
            ->leftJoin(AccountAccess::class, 'aa', Join::WITH, 'aa.account = a')
            ->where($builder->expr()->orX(
                $builder->expr()->eq('a.user', ':user'),
                $builder->expr()->eq('aa.user', ':user'),
            ))
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->andWhere('a.isDeleted = false')
            ->getQuery()
            ->getResult();
    }

    public function get(Id $id): Account
    {
        $item = $this->find($id);
        if (!$item instanceof Account) {
            throw new NotFoundException(sprintf('Account with ID %s not found', $id));
        }

        if ($item->isDeleted()) {
            throw new NotFoundException(sprintf('Account with ID %s not found', $id));
        }

        return $item;
    }

    public function delete(Id $id): void
    {
        $account = $this->get($id);
        $this->getEntityManager()->remove($account);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): Account
    {
        return $this->getEntityManager()->getReference(Account::class, $id);
    }

    public function getAccountsBalancesBeforeDate(array $accountIds, DateTimeInterface $date): array
    {
        if ($accountIds === []) {
            return [];
        }

        $parametersString = implode("', '", array_map(static fn(Id $id): string => $id->getValue(), $accountIds));
        $dateString = $date->format('Y-m-d H:i:s');
        $sql =<<<SQL
SELECT a.id as account_id,
       a.currency_id,
       COALESCE(incomes, 0) + COALESCE(transfer_incomes, 0) - COALESCE(expenses, 0) - COALESCE(transfer_expenses, 0) as balance
FROM accounts a
       LEFT JOIN (
           SELECT tmp.account_id, SUM(tmp.expenses) as expenses, SUM(tmp.incomes) as incomes, SUM(tmp.transfer_expenses) as transfer_expenses, SUM(tmp.transfer_incomes) as transfer_incomes FROM (
                SELECT tr1.account_id,
                       (SELECT SUM(t1.amount) FROM transactions t1 WHERE t1.account_id = tr1.account_id AND t1.type = 0 AND t1.spent_at < '{$dateString}') as expenses,
                       (SELECT SUM(t2.amount) FROM transactions t2 WHERE t2.account_id = tr1.account_id AND t2.type = 1 AND t2.spent_at < '{$dateString}') as incomes,
                       (SELECT SUM(t3.amount) FROM transactions t3 WHERE t3.account_id = tr1.account_id AND t3.type = 2 AND t3.spent_at < '{$dateString}') as transfer_expenses,
                       NULL as transfer_incomes
                FROM transactions tr1
                WHERE tr1.spent_at < '{$dateString}'
                GROUP BY tr1.account_id
                UNION ALL
                SELECT tr2.account_recipient_id as account_id,
                       NULL as expenses,
                       NULL as incomes,
                       NULL as transfer_expenses,
                       (SELECT SUM(t4.amount_recipient) FROM transactions t4 WHERE t4.account_recipient_id = tr2.account_recipient_id AND t4.type = 2 AND t4.spent_at < '{$dateString}') as transfer_incomes
                FROM transactions tr2
                WHERE tr2.account_recipient_id IS NOT NULL AND tr2.spent_at < '{$dateString}'
                GROUP BY tr2.account_recipient_id) tmp GROUP BY tmp.account_id
       ) t ON a.id = t.account_id AND a.id IN ('{$parametersString}');
SQL;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('account_id', 'account_id');
        $rsm->addScalarResult('currency_id', 'currency_id');
        $rsm->addScalarResult('balance', 'balance', 'string');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }

    public function getAccountsBalancesOnDate(array $accountIds, DateTimeInterface $date): array
    {
        if ($accountIds === []) {
            return [];
        }

        $parametersString = implode("', '", array_map(static fn(Id $id): string => $id->getValue(), $accountIds));
        $dateString = $date->format('Y-m-d H:i:s');
        $sql =<<<SQL
SELECT a.id as account_id,
       a.currency_id,
       COALESCE(incomes, 0) + COALESCE(transfer_incomes, 0) - COALESCE(expenses, 0) - COALESCE(transfer_expenses, 0) as balance
FROM accounts a
       LEFT JOIN (
           SELECT tmp.account_id, SUM(tmp.expenses) as expenses, SUM(tmp.incomes) as incomes, SUM(tmp.transfer_expenses) as transfer_expenses, SUM(tmp.transfer_incomes) as transfer_incomes FROM (
                SELECT tr1.account_id,
                       (SELECT SUM(t1.amount) FROM transactions t1 WHERE t1.account_id = tr1.account_id AND t1.type = 0 AND t1.spent_at <= '{$dateString}') as expenses,
                       (SELECT SUM(t2.amount) FROM transactions t2 WHERE t2.account_id = tr1.account_id AND t2.type = 1 AND t2.spent_at <= '{$dateString}') as incomes,
                       (SELECT SUM(t3.amount) FROM transactions t3 WHERE t3.account_id = tr1.account_id AND t3.type = 2 AND t3.spent_at <= '{$dateString}') as transfer_expenses,
                       NULL as transfer_incomes
                FROM transactions tr1
                WHERE tr1.spent_at <= '{$dateString}'
                GROUP BY tr1.account_id
                UNION ALL
                SELECT tr2.account_recipient_id as account_id,
                       NULL as expenses,
                       NULL as incomes,
                       NULL as transfer_expenses,
                       (SELECT SUM(t4.amount_recipient) FROM transactions t4 WHERE t4.account_recipient_id = tr2.account_recipient_id AND t4.type = 2 AND t4.spent_at <= '{$dateString}') as transfer_incomes
                FROM transactions tr2
                WHERE tr2.account_recipient_id IS NOT NULL AND tr2.spent_at <= '{$dateString}'
                GROUP BY tr2.account_recipient_id) tmp GROUP BY tmp.account_id
       ) t ON a.id = t.account_id AND a.id IN ('{$parametersString}');
SQL;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('account_id', 'account_id');
        $rsm->addScalarResult('currency_id', 'currency_id');
        $rsm->addScalarResult('balance', 'balance', 'string');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }

    public function getAccountsReport(array $accountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array
    {
        if ($accountIds === []) {
            return [];
        }

        $accounts = [];
        foreach ($accountIds as $accountId) {
            $accounts[] = $accountId->getValue();
        }

        $accountsString = implode("', '", $accounts);
        $periodStartString = $periodStart->format('Y-m-d H:i:s');
        $periodEndString = $periodEnd->format('Y-m-d H:i:s');
        $sql =<<<SQL
SELECT a.id as account_id,
       a.currency_id,
       COALESCE(incomes, 0) as incomes,
       COALESCE(transfer_incomes, 0) as transfer_incomes,
       COALESCE(exchange_incomes, 0) as exchange_incomes,
       COALESCE(expenses, 0) as expenses,
       COALESCE(transfer_expenses, 0) as transfer_expenses,
       COALESCE(exchange_expenses, 0) as exchange_expenses
FROM accounts a
       LEFT JOIN (
           SELECT tmp.account_id, SUM(tmp.expenses) as expenses, SUM(tmp.incomes) as incomes, SUM(tmp.transfer_expenses) as transfer_expenses, SUM(tmp.transfer_incomes) as transfer_incomes, SUM(tmp.exchange_expenses) as exchange_expenses, SUM(tmp.exchange_incomes) as exchange_incomes FROM (
                SELECT tr1.account_id,
                       (SELECT SUM(t1.amount) FROM transactions t1 WHERE t1.account_id = tr1.account_id AND t1.type = 0 AND t1.spent_at >= '{$periodStartString}' AND t1.spent_at < '{$periodEndString}') as expenses,
                       (SELECT SUM(t2.amount) FROM transactions t2 WHERE t2.account_id = tr1.account_id AND t2.type = 1 AND t2.spent_at >= '{$periodStartString}' AND t2.spent_at < '{$periodEndString}') as incomes,
                       (SELECT SUM(t3.amount) FROM transactions t3 WHERE t3.account_id = tr1.account_id AND t3.type = 2 AND t3.spent_at >= '{$periodStartString}' AND t3.spent_at < '{$periodEndString}') as transfer_expenses,
                       NULL as transfer_incomes,
                       NULL as exchange_incomes,
                       (SELECT SUM(t4.amount) FROM transactions t4 WHERE t4.account_id = tr1.account_id AND t4.type = 2 AND t4.amount != t4.amount_recipient AND t4.spent_at >= '{$periodStartString}' AND t4.spent_at < '{$periodEndString}') as exchange_expenses
                FROM transactions tr1
                WHERE tr1.spent_at >= '{$periodStartString}' AND tr1.spent_at < '{$periodEndString}'
                GROUP BY tr1.account_id
                UNION ALL
                SELECT tr2.account_recipient_id as account_id,
                       NULL as expenses,
                       NULL as incomes,
                       NULL as transfer_expenses,
                       (SELECT SUM(t5.amount_recipient) FROM transactions t5 WHERE t5.account_recipient_id = tr2.account_recipient_id AND t5.type = 2 AND t5.spent_at >= '{$periodStartString}' AND t5.spent_at < '{$periodEndString}') as transfer_incomes,
                       (SELECT SUM(t6.amount_recipient) FROM transactions t6 WHERE t6.account_recipient_id = tr2.account_recipient_id AND t6.type = 2 AND t6.amount != t6.amount_recipient AND t6.spent_at >= '{$periodStartString}' AND t6.spent_at < '{$periodEndString}') as exchange_incomes,
                       NULL as exchange_expenses
                FROM transactions tr2
                WHERE tr2.account_recipient_id IS NOT NULL AND tr2.spent_at >= '{$periodStartString}' AND tr2.spent_at < '{$periodEndString}'
                GROUP BY tr2.account_recipient_id) tmp GROUP BY tmp.account_id
       ) t ON a.id = t.account_id AND a.id IN ('{$accountsString}');
SQL;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('account_id', 'account_id');
        $rsm->addScalarResult('currency_id', 'currency_id');
        $rsm->addScalarResult('incomes', 'incomes', 'string');
        $rsm->addScalarResult('transfer_incomes', 'transfer_incomes', 'string');
        $rsm->addScalarResult('exchange_incomes', 'exchange_incomes', 'string');
        $rsm->addScalarResult('expenses', 'expenses', 'string');
        $rsm->addScalarResult('transfer_expenses', 'transfer_expenses', 'string');
        $rsm->addScalarResult('exchange_expenses', 'exchange_expenses', 'string');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }

    public function getHoldingsReport(
        array $reportAccountIds,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd
    ): array {
        if ($reportAccountIds === []) {
            return [];
        }

        $reportAccounts = [];
        foreach ($reportAccountIds as $accountId) {
            $reportAccounts[] = $accountId->getValue();
        }

        $reportAccountsString = implode("', '", $reportAccounts);
        $periodStartString = $periodStart->format('Y-m-d H:i:s');
        $periodEndString = $periodEnd->format('Y-m-d H:i:s');

        $sql =<<<SQL
SELECT SUM(t.amount_recipient) as amount, a.currency_id FROM transactions t
LEFT JOIN accounts a ON t.account_recipient_id = a.id
WHERE t.amount = t.amount_recipient AND t.account_recipient_id NOT IN ('{$reportAccountsString}') AND t.account_id IN ('{$reportAccountsString}') AND t.type = 2 AND t.spent_at >= '{$periodStartString}' AND t.spent_at < '{$periodEndString}'
GROUP BY a.currency_id;
SQL;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('currency_id', 'currency_id');
        $rsm->addScalarResult('amount', 'amount', 'string');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $toHoldings = $query->getResult();

        $sql =<<<SQL
SELECT SUM(t.amount) as amount, a.currency_id FROM transactions t
LEFT JOIN accounts a ON t.account_id = a.id
WHERE t.amount = t.amount_recipient AND t.account_recipient_id IN ('{$reportAccountsString}') AND t.account_id NOT IN ('{$reportAccountsString}') AND t.type = 2 AND t.spent_at >= '{$periodStartString}' AND t.spent_at < '{$periodEndString}'
GROUP BY a.currency_id;
SQL;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('currency_id', 'currency_id');
        $rsm->addScalarResult('amount', 'amount', 'string');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $fromHoldings = $query->getResult();

        $result = [];
        foreach ($toHoldings as $item) {
            if (!isset($result[$item['currency_id']])) {
                $result[$item['currency_id']] = [
                    'to_holdings' => new DecimalNumber(),
                    'from_holdings' => new DecimalNumber(),
                ];
            }

            $result[$item['currency_id']]['to_holdings'] = $result[$item['currency_id']]['to_holdings']->add($item['amount']);
        }

        foreach ($fromHoldings as $item) {
            if (!isset($result[$item['currency_id']])) {
                $result[$item['currency_id']] = [
                    'to_holdings' => new DecimalNumber(),
                    'from_holdings' => new DecimalNumber(),
                ];
            }

            $result[$item['currency_id']]['from_holdings'] = $result[$item['currency_id']]['from_holdings']->add($item['amount']);
        }

        return $result;
    }

    public function getUserAccountsForBudgeting(Id $userId): array
    {
        $builder = $this->createQueryBuilder('a');
        return $builder
            ->select('a')
            ->where('a.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->andWhere('a.isDeleted = false')
            ->getQuery()
            ->getResult();
    }

    public function findByOwnersIds(array $userIds): array
    {
        $users = [];
        foreach ($userIds as $userId) {
            $users[] = $this->getEntityManager()->getReference(User::class, $userId);
        }

        $builder = $this->createQueryBuilder('a');
        $builder->select('a')
            ->where($builder->expr()->in('a.user', ':users'))
            ->setParameter('users', $users)
            ->andWhere('a.isDeleted = false');
        return $builder->getQuery()->getResult();
    }
}
