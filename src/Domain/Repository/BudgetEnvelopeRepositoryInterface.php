<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BudgetEnvelope;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface BudgetEnvelopeRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return BudgetEnvelope[]
     */
    public function getByBudgetId(Id $budgetId): array;

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
}