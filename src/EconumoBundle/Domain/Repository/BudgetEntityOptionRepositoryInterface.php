<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Repository;

use App\EconumoBundle\Domain\Entity\BudgetEntityOption;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;

interface BudgetEntityOptionRepositoryInterface
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
}