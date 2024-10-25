<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetElementAmount;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetElementAmountRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetElementAmount[]
     */
    public function getByBudgetId(Id $budgetId, DateTimeInterface $period): array;

    /**
     * @param BudgetElementAmount[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetElementAmount[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function deleteByBudgetId(Id $budgetId): void;

    /**
     * @param Id $budgetId
     * @param Id $elementId
     * @param BudgetElementType $elementType
     * @return void
     */
    public function deleteByElementIdAndType(Id $budgetId, Id $elementId, BudgetElementType $elementType): void;

    public function getSummarizedAmounts(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;

    /**
     * @param Id $budgetId
     * @param Id[] $sourceElementsIds
     * @param BudgetElementType $targetElementType
     * @return float[]|int[]
     */
    public function getSummarizedAmountsForElements(Id $budgetId, array $sourceElementsIds, BudgetElementType $targetElementType): array;

    /**
     * @param Id $budgetId
     * @param Id $targetElementId
     * @param BudgetElementType $targetElementType
     * @return BudgetElementAmount[]
     */
    public function getByElementIdAndType(Id $budgetId, Id $targetElementId, BudgetElementType $targetElementType): array;

    /**
     * @param Id $budgetId
     * @param Id $elementId
     * @param DateTimeInterface $period
     * @return BudgetElementAmount|null
     */
    public function get(Id $budgetId, Id $elementId, DateTimeInterface $period): ?BudgetElementAmount;
}