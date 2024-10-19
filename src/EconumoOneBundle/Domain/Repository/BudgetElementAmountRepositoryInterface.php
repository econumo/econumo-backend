<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetElementAmount;
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

    public function getSummarizedAmounts(Id $budgetId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array;
}