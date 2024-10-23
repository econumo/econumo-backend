<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetElementOptionRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetElementOption[]
     */
    public function getByBudgetId(Id $budgetId): array;

    public function get(Id $budgetId, Id $elementId, BudgetElementType $elementType): BudgetElementOption;

    /**
     * @param BudgetElementOption[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetElementOption[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function getReference(Id $id): BudgetElementOption;

    public function deleteByElementIdAndType(Id $budgetId, Id $elementId, BudgetElementType $elementType): void;
}