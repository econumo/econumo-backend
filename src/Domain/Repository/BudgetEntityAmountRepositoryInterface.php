<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BudgetEntityAmount;
use App\Domain\Entity\ValueObject\Id;
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
}