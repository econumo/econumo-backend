<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Budget;
use App\Domain\Entity\BudgetEntityAmount;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetEntityAmountRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
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
}
