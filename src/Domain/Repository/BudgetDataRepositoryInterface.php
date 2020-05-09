<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BudgetData;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetDataRepositoryInterface
{
    /**
     * @param Id $budgetId
     * @param DateTimeInterface $fromDate
     * @param DateTimeInterface $toDate
     * @return BudgetData[]
     */
    public function findByBudgetId(Id $budgetId, DateTimeInterface $fromDate, DateTimeInterface $toDate): array;
}
