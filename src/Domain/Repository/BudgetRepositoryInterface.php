<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;

interface BudgetRepositoryInterface
{
    public function getNextIdentity(): Id;

    public function get(Id $id): Budget;

    /**
     * @param Id $userId
     * @return Budget[]
     */
    public function getByUserId(Id $userId): array;

    /**
     * @param Budget[] $items
     */
    public function save(array $items): void;

    public function getReference(Id $id): Budget;

    /**
     * @param Budget[] $items
     */
    public function delete(array $items): void;
}
