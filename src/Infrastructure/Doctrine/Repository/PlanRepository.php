<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Plan;
use App\Domain\Entity\PlanAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\PlanRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method Plan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plan[]    findAll()
 * @method Plan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanRepository extends ServiceEntityRepository implements PlanRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plan::class);
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
    public function save(array $items): void
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
    public function getAvailableForUserId(Id $userId): array
    {
        $builder = $this->createQueryBuilder('p');
        return $builder
            ->select('p')
            ->leftJoin(PlanAccess::class, 'pa', Join::WITH, 'pa.plan = p')
            ->where($builder->expr()->orX(
                $builder->expr()->eq('p.user', ':user'),
                $builder->expr()->eq('pa.user', ':user'),
            ))
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function getUserPlans(Id $userId): array
    {
        $builder = $this->createQueryBuilder('a');
        return $builder
            ->select('p')
            ->leftJoin(PlanAccess::class, 'pa', Join::WITH, 'pa.plan = p')
            ->where($builder->expr()->orX(
                $builder->expr()->eq('p.user', ':user'),
                $builder->expr()->eq('pa.user', ':user'),
            ))
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findByOwnerId(Id $userId): array
    {
        return $this->findBy(['user' => $this->getEntityManager()->getReference(User::class, $userId)]);
    }

    public function get(Id $id): Plan
    {
        $item = $this->find($id);
        if (!$item instanceof Plan) {
            throw new NotFoundException(sprintf('Plan with ID %s not found', $id));
        }

        return $item;
    }

    public function delete(Plan $plan): void
    {
        $this->getEntityManager()->remove($plan);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): Plan
    {
        return $this->getEntityManager()->getReference(Plan::class, $id);
    }
}
