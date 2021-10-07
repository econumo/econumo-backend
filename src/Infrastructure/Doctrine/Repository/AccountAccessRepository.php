<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
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
            $this->getEntityManager()->beginTransaction();
            foreach ($items as $item) {
                $this->getEntityManager()->persist($item);
            }
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            $this->getEntityManager()->rollback();
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(Id $userId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.userId = :id')
            ->setParameter('id', $userId->getValue())
            ->orderBy('a.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function get(Id $accountId, Id $userId): AccountAccess
    {
        /** @var AccountAccess|null $item */
        $item = $this->findOneBy(['accountId' => $accountId, 'userId' => $userId]);
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
        return $this->findBy(['accountId' => $accountId]);
    }
}