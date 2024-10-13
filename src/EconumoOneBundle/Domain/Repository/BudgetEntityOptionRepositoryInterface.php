<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetEntityOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

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