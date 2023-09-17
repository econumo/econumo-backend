<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

interface PlanRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Plan[]
     */
    public function getAvailableForUserId(Id $userId): array;

    /**
     * @return Plan[]
     */
    public function getUserPlans(Id $userId): array;

    public function get(Id $id): Plan;

    /**
     * @param Plan[] $items
     */
    public function save(array $items): void;

    public function delete(Id $id): void;

    public function getReference(Id $id): Plan;
}
