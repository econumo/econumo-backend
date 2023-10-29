<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Plan;
use App\Domain\Entity\PlanAccess;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\PlanAccessRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method PlanAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanAccess[]    findAll()
 * @method PlanAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanAccessRepository extends ServiceEntityRepository implements PlanAccessRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanAccess::class);
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
    public function findByUserId(Id $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->orderBy('p.position', Criteria::ASC)
            ->getQuery()
            ->getResult();
    }

    public function get(Id $planId, Id $userId): PlanAccess
    {
        $item = $this->findOneBy([
            'plan' => $this->getEntityManager()->getReference(Plan::class, $planId),
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
        if (!$item instanceof PlanAccess) {
            throw new NotFoundException('PlanAccess not found');
        }

        return $item;
    }

    public function delete(PlanAccess $item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    /**
     * @inheritDoc
     */
    public function getByPlanId(Id $planId): array
    {
        return $this->findBy(['plan' => $this->getEntityManager()->getReference(Plan::class, $planId)]);
    }

    /**
     * @inheritDoc
     */
    public function getOwnedByUser(Id $userId): array
    {
        $dql = <<<'DQL'
SELECT p.id FROM App\Domain\Entity\PlanAccess pa
JOIN App\Domain\Entity\Plan p WITH p = pa.plan AND pa.user = :user
GROUP BY p.id
DQL;
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $plans = array_map(fn($id): ?Plan => $this->getEntityManager()->getReference(Plan::class, new Id($id)), array_column($query->getScalarResult(), 'id'));

        return $this->findBy(['plan' => $plans]);
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
SELECT p.id FROM App\Domain\Entity\PlanAccess pa
JOIN App\Domain\Entity\Plan p WITH p = pa.plan AND p.user = :user
GROUP BY p.id
DQL;
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId));
        $plans = array_map(fn($id): ?Plan => $this->getEntityManager()->getReference(Plan::class, new Id($id)), array_column($query->getScalarResult(), 'id'));

        return $this->findBy(['plan' => $plans]);
    }
}
