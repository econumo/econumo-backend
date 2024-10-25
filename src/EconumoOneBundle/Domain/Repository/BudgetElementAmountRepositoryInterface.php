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

    public function deleteByElementId(Id $elementId): void;

    /**
     * @param Id $budgetId
     * @param Id $elementId
     * @return void
     */
    public function deleteByBudgetIdAndElementId(Id $budgetId, Id $elementId): void;

    public function getSummarizedAmountsForPeriod(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;

    /**
     * @param Id $budgetId
     * @param Id[] $elementsIds
     * @return float[]|int[]
     */
    public function getSummarizedAmountsForElements(Id $budgetId, array $elementsIds): array;

    /**
     * @param Id $budgetId
     * @param Id $elementId
     * @return BudgetElementAmount[]
     */
    public function getByBudgetIdAndElementId(Id $budgetId, Id $elementId): array;

    /**
     * @param Id $budgetId
     * @param Id $elementId
     * @param DateTimeInterface $period
     * @return BudgetElementAmount|null
     */
    public function get(Id $budgetId, Id $elementId, DateTimeInterface $period): ?BudgetElementAmount;
}