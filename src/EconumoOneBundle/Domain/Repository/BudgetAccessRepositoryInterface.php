<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;

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
    public function getPendingAccess(Id $userId): array;

    public function getReference(Id $id): BudgetAccess;

    /**
     * @param Id $userId
     * @return BudgetAccess[]
     */
    public function getByUser(Id $userId): array;
}