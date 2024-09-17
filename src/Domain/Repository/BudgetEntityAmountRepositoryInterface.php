<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BudgetEntityOption;
use App\Domain\Entity\ValueObject\Id;

interface BudgetEntityAmountRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetEntityOption[]
     */
    public function getByBudgetId(Id $budgetId): array;

    /**
     * @param BudgetEntityOption[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetEntityOption[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function deleteByBudgetId(Id $budgetId): void;
}