<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountRepositoryInterface;
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

        $parameters = [];
        foreach ($accountIds as $accountId) {
            $parameters[] = $accountId->getValue();
        }
        $parametersString = implode("', '", $parameters);
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
        $rsm->addScalarResult('balance', 'balance', 'float');
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }

    public function getUserAccountsForBudgeting(Id $userId): array
    {
        $builder = $this->createQueryBuilder('a');
        return $builder
            ->select('a')
            ->where('a.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->andWhere('a.isDeleted = false')
            ->andWhere('a.isExcludedFromBudget = false')
            ->getQuery()
            ->getResult();
    }
}
