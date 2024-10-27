<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\BudgetElementLimit;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface BudgetElementLimitRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetElementLimit[]
     */
    public function getByBudgetIdAndPeriod(Id $budgetId, DateTimeInterface $period): array;

    /**
     * @param BudgetElementLimit[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetElementLimit[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function deleteByBudgetId(Id $budgetId): void;

    public function deleteByElementId(Id $elementId): void;

    public function getSummarizedAmountsForPeriod(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;

    /**
     * @param Id $budgetId
     * @param Id[] $externalIds
     * @return float[]|int[]
     */
    public function getSummarizedAmountsForElements(Id $budgetId, array $externalIds): array;

    /**
     * @param Id $budgetId
     * @param Id $externalId
     * @return BudgetElementLimit[]
     */
    public function getByBudgetIdAndElementId(Id $budgetId, Id $externalId): array;

    /**
     * @param Id $elementId
     * @param DateTimeInterface $period
     * @return BudgetElementLimit|null
     */
    public function get(Id $elementId, DateTimeInterface $period): ?BudgetElementLimit;
}