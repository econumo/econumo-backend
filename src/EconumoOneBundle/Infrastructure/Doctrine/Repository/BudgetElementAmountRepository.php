<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElementAmount;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetElementAmountRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetElementAmount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetElementAmount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetElementAmount[]    findAll()
 * @method BudgetElementAmount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetElementAmountRepository extends ServiceEntityRepository implements BudgetElementAmountRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetElementAmount::class);
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

    public function getSummarizedAmountsForPeriod(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array
    {
        $query = $this->createQueryBuilder('ea')
            ->select('ea.elementId, ea.elementType, SUM(ea.amount) as amount')
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->andWhere('ea.period >= :periodStart')
            ->setParameter('periodStart', $periodStart)
            ->andWhere('ea.period < :periodEnd')
            ->setParameter('periodEnd', $periodEnd)
            ->groupBy('ea.elementId, ea.elementType')
            ->getQuery();
        return $query->getArrayResult();
    }

    public function getSummarizedAmountsForElements(Id $budgetId, array $elementsIds): array
    {
        $elementsIds = [];
        foreach ($elementsIds as $sourceElementId) {
            $elementsIds[] = $sourceElementId->getValue();
        }
        $amountsQuery = $this->createQueryBuilder('ea')
            ->select('SUM(ea.amount) as amount, ea.period')
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->andWhere('ea.elementId IN (:elementsIds)')
            ->setParameter('elementsIds', $elementsIds)
            ->groupBy('ea.period')
            ->orderBy('ea.period')
            ->getQuery();
        $sourceAmounts = [];
        foreach($amountsQuery->getArrayResult() as $item) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $item['period'])->format('Y-m-d');
            $sourceAmounts[$date] = $item['amount'];
        }

        return $sourceAmounts;
    }

    public function getByBudgetIdAndElementId(Id $budgetId, Id $elementId): array
    {
        $targetAmountQuery = $this->createQueryBuilder('ea')
            ->select('ea')
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->andWhere('ea.elementId = :elementId')
            ->setParameter('elementId', $elementId->getValue())
            ->orderBy('ea.period')
            ->getQuery();
        return $targetAmountQuery->getResult();
    }

    public function deleteByBudgetIdAndElementId(Id $budgetId, Id $elementId): void {
        $this->createQueryBuilder('ea')
            ->delete()
            ->where('ea.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->andWhere('ea.elementId = :elementId')
            ->setParameter('elementId', $elementId->getValue())
            ->getQuery()
            ->execute();
    }

    public function get(Id $budgetId, Id $elementId, DateTimeInterface $period): ?BudgetElementAmount
    {
        return $this->findOneBy([
            'budget' => $this->getEntityReference(Budget::class, $budgetId),
            'elementId' => $elementId,
            'period' => $period
        ]);
    }

    public function deleteByElementId(Id $elementId): void
    {
        $this->createQueryBuilder('ea')
            ->delete()
            ->where('ea.elementId = :elementId')
            ->setParameter('elementId', $elementId->getValue())
            ->getQuery()
            ->execute();
    }
}
