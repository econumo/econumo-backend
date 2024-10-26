<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\BudgetElementLimit;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetElementLimitRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method BudgetElementLimit|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetElementLimit|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetElementLimit[]    findAll()
 * @method BudgetElementLimit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetElementLimitRepository extends ServiceEntityRepository implements BudgetElementLimitRepositoryInterface
{
    use NextIdentityTrait;

    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetElementLimit::class);
    }

    public function getByBudgetId(Id $budgetId, DateTimeInterface $period): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $query = $this->createQueryBuilder('ea')
            ->select()
            ->join('ea.element', 'e', 'WITH', 'e.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->where('ea.period = :period')
            ->setParameter('period', $date)
            ->getQuery();
        return $query->getResult();
    }

    public function deleteByBudgetId(Id $budgetId): void
    {
        $this->createQueryBuilder('ea')
            ->delete()
            ->join('ea.element', 'e', 'WITH', 'e.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->getQuery()
            ->execute();
    }

    public function getSummarizedAmountsForPeriod(
        Id $budgetId,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd
    ): array {
        $query = $this->createQueryBuilder('el')
            ->select('e.externalId as elementId, e.type as elementType, SUM(el.amount) as amount')
            ->join('el.element', 'e', 'WITH', 'e.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->where('el.period >= :periodStart')
            ->setParameter('periodStart', $periodStart)
            ->andWhere('el.period < :periodEnd')
            ->setParameter('periodEnd', $periodEnd)
            ->groupBy('e.externalId, e.type')
            ->getQuery();

        return $query->getArrayResult();
    }

    public function getSummarizedAmountsForElements(Id $budgetId, array $elementsIds): array
    {
        $amountsQuery = $this->createQueryBuilder('el')
            ->select('SUM(el.amount) as amount, el.period')
            ->join('el.element', 'e', 'WITH', 'e.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->where('e.externalId IN (:elements)')
            ->setParameter('elements', $elementsIds)
            ->groupBy('el.period')
            ->orderBy('el.period')
            ->getQuery();
        $sourceAmounts = [];
        foreach ($amountsQuery->getArrayResult() as $item) {
            $date = DateTime::createFromInterface($item['period'])->format('Y-m-d');
            $sourceAmounts[$date] = $item['amount'];
        }

        return $sourceAmounts;
    }

    public function getByBudgetIdAndElementId(Id $budgetId, Id $elementId): array
    {
        $targetAmountQuery = $this->createQueryBuilder('el')
            ->select('el')
            ->join('el.element', 'e', 'WITH', 'e.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->where('e.externalId = :elementId')
            ->setParameter('elementId', $elementId)
            ->orderBy('el.period')
            ->getQuery();
        return $targetAmountQuery->getResult();
    }

    public function deleteByBudgetIdAndElementId(Id $budgetId, Id $elementId): void
    {
        $budgetRef = $this->getEntityReference(Budget::class, $budgetId);
        $this->createQueryBuilder('el')
            ->delete()
            ->join('el.element', 'e', 'WITH', 'e.budget = :budget')
            ->setParameter('budget', $budgetRef)
            ->where('e.externalId = :elementId')
            ->setParameter('elementId', $elementId)
            ->getQuery()
            ->execute();
    }

    public function get(BudgetElement $element, DateTimeInterface $period): ?BudgetElementLimit
    {
        $period = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-d 00:00:00'));
        $builder = $this->createQueryBuilder('el');
        $builder->select('el')
            ->where('el.element = :element')
            ->andWhere('el.period = :period')
            ->setParameter('element', $element)
            ->setParameter('period', $period);
        return $builder->getQuery()->getOneOrNullResult();
    }

    public function deleteByElementId(Id $elementId): void
    {
        throw new \RuntimeException('Not implemented');
    }
}
