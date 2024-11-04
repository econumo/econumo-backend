<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;

interface BudgetEnvelopeRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetEnvelope[]
     */
    public function getByBudgetId(Id $budgetId, bool $onlyActive = null): array;

    /**
     * @throws NotFoundException
     */
    public function get(Id $id): BudgetEnvelope;

    /**
     * @param BudgetEnvelope[] $items
     * @return void
     */
    public function save(array $items): void;

    /**
     * @param BudgetEnvelope[] $items
     * @return void
     */
    public function delete(array $items): void;

    public function getReference(Id $id): BudgetEnvelope;

    /**
     * @param Id $budgetId
     * @param Id[] $categoriesIds
     * @return void
     */
    public function deleteAssociationsWithCategories(Id $budgetId, array $categoriesIds): void;
}