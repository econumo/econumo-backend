<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\PlanExchangeBudget;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

interface PlanExchangeBudgetRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return PlanExchangeBudget[]
     */
    public function getByPlanAndCurrencyId(Id $planId, Id $currencyId, DateTimeInterface $period): array;

    public function get(Id $id): PlanExchangeBudget;

    /**
     * @param PlanExchangeBudget[] $items
     */
    public function save(array $items): void;

    public function delete(PlanExchangeBudget $item): void;

    public function getReference(Id $id): PlanExchangeBudget;
}
