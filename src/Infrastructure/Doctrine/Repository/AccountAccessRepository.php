<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method AccountAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountAccess[]    findAll()
 * @method AccountAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountAccessRepository extends ServiceEntityRepository implements AccountAccessRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountAccess::class);
    }

    public function save(AccountAccess ...$items): void
    {
        try {
            foreach ($items as $item) {
                $this->getEntityManager()->persist($item);
            }
            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(Id $userId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->orderBy('a.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function get(Id $accountId, Id $userId): AccountAccess
    {
        /** @var AccountAccess|null $item */
        $item = $this->findOneBy([
            'account' => $this->getEntityManager()->getReference(Account::class, $accountId),
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
        if ($item === null) {
            throw new NotFoundException('AccountAccess not found');
        }

        return $item;
    }

    public function delete(Id $accountId, Id $userId): void
    {
        $item = $this->get($accountId, $userId);
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    /**
     * @inheritDoc
     */
    public function getByAccount(Id $accountId): array
    {
        return $this->findBy(['account' => $this->getEntityManager()->getReference(Account::class, $accountId)]);
    }

    /**
     * @inheritDoc
     */
    public function getOwnedByUser(Id $userId): array
    {
        $dql = <<<'DQL'
SELECT a.id FROM App\Domain\Entity\AccountAccess aa
JOIN App\Domain\Entity\Account a WITH a = aa.account AND aa.user = :user
GROUP BY a.id
DQL;
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $accounts = array_map(function ($id) {
            return $this->getEntityManager()->getReference(Account::class, new Id($id));
        }, array_column($query->getScalarResult(), 'id'));

        return $this->findBy(['account' => $accounts]);
    }

    /**
     * @inheritDoc
     */
    public function getReceivedAccess(Id $userId): array
    {
        return $this->findBy([
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
    }

    public function getIssuedAccess(Id $userId): array
    {
        $dql = <<<'DQL'
SELECT a.id FROM App\Domain\Entity\AccountAccess aa
JOIN App\Domain\Entity\Account a WITH a = aa.account AND a.user = :user
GROUP BY a.id
DQL;
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $accounts = array_map(function ($id) {
            return $this->getEntityManager()->getReference(Account::class, new Id($id));
        }, array_column($query->getScalarResult(), 'id'));

        return $this->findBy(['account' => $accounts]);
    }
}
