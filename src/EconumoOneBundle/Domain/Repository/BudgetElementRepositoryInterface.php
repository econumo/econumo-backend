<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface BudgetElementRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetElement[]
     */
    public function getByBudgetId(Id $budgetId): array;

    public function get(Id $budgetId, Id $externalElementId): BudgetElement;

    /**
     * @param BudgetElement[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetElement[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function getReference(Id $id): BudgetElement;

    public function getNextPosition(Id $budgetId, ?Id $folderId): int;

    /**
     * @param Id $externalElementId
     * @return BudgetElement[]
     */
    public function getElementsByExternalId(Id $externalElementId): array;
}