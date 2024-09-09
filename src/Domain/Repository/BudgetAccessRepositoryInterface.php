<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BudgetAccess;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface BudgetAccessRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetAccess[]
     */
    public function getByBudgetId(Id $budgetId): array;

    /**
     * @param BudgetAccess[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @throws NotFoundException
     */
    public function get(Id $budgetId, Id $userId): BudgetAccess;

    /**
     * @param BudgetAccess[] $items
     * @return void
     */
    public function delete(array $items): void;

    /**
     * @return BudgetAccess[]
     */
    public function getPendingAccesses(Id $userId): array;

    public function getReference(Id $id): BudgetAccess;
}