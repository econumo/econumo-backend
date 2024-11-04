<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

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
