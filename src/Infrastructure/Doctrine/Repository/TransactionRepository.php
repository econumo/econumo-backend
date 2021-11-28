<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\TransactionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
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
    public function findByAccountId(Id $id): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.accountId = :id')
            ->orWhere('c.accountRecipientId = :id')
            ->setParameter('id', $id->getValue())
            ->orderBy('c.spentAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(Transaction ...$transactions): void
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
        /** @var Transaction|null $item */
        $item = $this->find($id);
        if ($item === null) {
            throw new NotFoundException(sprintf('Transaction with ID %s not found', $id));
        }

        return $item;
    }

    public function findByUserId(Id $userId): array
    {
        $sharedAccountsQuery = $this->getEntityManager()
            ->createQuery('SELECT aa.accountId FROM App\Domain\Entity\AccountAccess aa WHERE aa.userId = :id')
            ->setParameter('id', $userId->getValue());
        $sharedIds = array_column($sharedAccountsQuery->getScalarResult(), 'accountId');

        $accountsQuery = $this->getEntityManager()
            ->createQuery('SELECT a.id FROM App\Domain\Entity\Account a WHERE a.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $userAccountIds = array_column($accountsQuery->getScalarResult(), 'id');
        $ids = array_unique(array_merge($sharedIds, $userAccountIds));

        $query = $this->createQueryBuilder('t')
            ->where('t.accountId IN(:ids) OR t.accountRecipientId IN(:ids)')
            ->setParameter('ids', $ids);

        return $query->getQuery()->getResult();
    }

    public function delete(Transaction $transaction): void
    {
        $this->getEntityManager()->remove($transaction);
    }
}
