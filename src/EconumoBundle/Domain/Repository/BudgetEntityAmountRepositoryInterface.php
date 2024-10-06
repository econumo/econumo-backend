<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Repository;

use App\EconumoBundle\Domain\Entity\BudgetEntityAmount;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetEntityAmountRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetEntityAmount[]
     */
    public function getByBudgetId(Id $budgetId, DateTimeInterface $period): array;

    /**
     * @param BudgetEntityAmount[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetEntityAmount[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function deleteByBudgetId(Id $budgetId): void;

    public function getSummarizedAmounts(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;
}