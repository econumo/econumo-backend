<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\Category;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\TransactionRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository implements TransactionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
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
    public function findByAccountId(Id $accountId): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.account = :account')
            ->orWhere('t.accountRecipient = :account')
            ->setParameter('account', $this->getEntityManager()->getReference(Account::class, $accountId))
            ->orderBy('t.spentAt', Criteria::DESC)
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function save(array $transactions): void
    {
        try {
            foreach ($transactions as $transaction) {
                $this->getEntityManager()->persist($transaction);
            }

            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Id $id): Transaction
    {
        $item = $this->find($id);
        if (!$item instanceof Transaction) {
            throw new NotFoundException(sprintf('Transaction with ID %s not found', $id));
        }

        return $item;
    }

    public function findAvailableForUserId(Id $userId): array
    {
        $sharedAccountsQuery = $this->getEntityManager()
            ->createQuery('SELECT IDENTITY(aa.account) as accountId FROM App\Domain\Entity\AccountAccess aa WHERE aa.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $sharedIds = array_column($sharedAccountsQuery->getScalarResult(), 'accountId');

        $accountsQuery = $this->getEntityManager()
            ->createQuery('SELECT a.id FROM App\Domain\Entity\Account a WHERE a.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $userAccountIds = array_column($accountsQuery->getScalarResult(), 'id');
        $accounts = array_map(fn(string $id): ?Account => $this->getEntityManager()->getReference(Account::class, new Id($id)), array_unique([...$sharedIds, ...$userAccountIds]));

        $query = $this->createQueryBuilder('t')
            ->where('t.account IN(:accounts) OR t.accountRecipient IN(:accounts)')
            ->setParameter('accounts', $accounts);

        return $query->getQuery()->getResult();
    }

    public function delete(Transaction $transaction): void
    {
        $this->getEntityManager()->remove($transaction);
        $this->getEntityManager()->flush();
    }

    public function replaceCategory(Id $oldCategoryId, Id $newCategoryId): void
    {
        $builder = $this->createQueryBuilder('t');
        $builder->update()
            ->set('t.category', ':newCategory')
            ->setParameter('newCategory', $this->getEntityManager()->getReference(Category::class, $newCategoryId))
            ->where('t.category = :oldCategory')
            ->setParameter('oldCategory', $this->getEntityManager()->getReference(Category::class, $oldCategoryId));
        $builder->getQuery()->execute();
    }

    public function findChanges(Id $userId, DateTimeInterface $lastUpdate): array
    {
        $sharedAccountsQuery = $this->getEntityManager()
            ->createQuery('SELECT IDENTITY(aa.account) as accountId FROM App\Domain\Entity\AccountAccess aa WHERE aa.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $sharedIds = array_column($sharedAccountsQuery->getScalarResult(), 'accountId');

        $accountsQuery = $this->getEntityManager()
            ->createQuery('SELECT a.id FROM App\Domain\Entity\Account a WHERE a.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $userAccountIds = array_column($accountsQuery->getScalarResult(), 'id');
        $accounts = array_map(fn(string $id): ?Account => $this->getEntityManager()->getReference(Account::class, new Id($id)), array_unique([...$sharedIds, ...$userAccountIds]));

        $query = $this->createQueryBuilder('t')
            ->where('t.account IN(:accounts) OR t.accountRecipient IN(:accounts)')
            ->andWhere('t.updatedAt > :lastUpdate')
            ->setParameter('accounts', $accounts)
            ->setParameter('lastUpdate', $lastUpdate);

        return $query->getQuery()->getResult();
    }
}
