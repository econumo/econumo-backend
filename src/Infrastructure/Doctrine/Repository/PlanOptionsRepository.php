<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Plan;
use App\Domain\Entity\PlanOptions;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\PlanOptionsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method PlanOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanOptions[]    findAll()
 * @method PlanOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanOptionsRepository extends ServiceEntityRepository implements PlanOptionsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanOptions::class);
    }

    public function getByUserId(Id $userId): array
    {
        $builder = $this->createQueryBuilder('po');
        return $builder
            ->where('po.user = :user')
            ->setParameter('user', $this->getEntityManager()->getReference(User::class, $userId))
            ->orderBy('po.position', Criteria::ASC)
            ->getQuery()
            ->getResult();
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
        } catch (ORMException|ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Id $planId, Id $userId): PlanOptions
    {
        $item = $this->findOneBy([
            'plan' => $this->getEntityManager()->getReference(Plan::class, $planId),
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
        if (!$item instanceof PlanOptions) {
            throw new NotFoundException(
                sprintf('PlanOptions for plan_id %s user_id %s not found', $planId, $userId)
            );
        }

        return $item;
    }

    public function delete(PlanOptions $item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }
}
