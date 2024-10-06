<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Repository;

use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Entity\BudgetEntityAmount;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetEntityAmount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetEntityAmount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetEntityAmount[]    findAll()
 * @method BudgetEntityAmount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetEntityAmountRepository extends ServiceEntityRepository implements BudgetEntityAmountRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetEntityAmount::class);
    }

    public function getByBudgetId(Id $budgetId, DateTimeInterface $period): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $query = $this->createQueryBuilder('ea')
            ->select()
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->andWhere('ea.period = :period')
            ->setParameter('period', $date)
            ->getQuery();
        return $query->getResult();
    }

    public function deleteByBudgetId(Id $budgetId): void
    {
        $this->createQueryBuilder('ea')
            ->delete()
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->getQuery()
            ->execute();
    }

    public function getSummarizedAmounts(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array
    {
        $query = $this->createQueryBuilder('ea')
            ->select('ea.entityId, ea.entityType, SUM(ea.amount) as amount')
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->andWhere('ea.period >= :periodStart')
            ->setParameter('periodStart', $periodStart)
            ->andWhere('ea.period < :periodEnd')
            ->setParameter('periodEnd', $periodEnd)
            ->groupBy('ea.entityId, ea.entityType')
            ->getQuery();
        return $query->getArrayResult();
    }
}