<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\AccountOptions;
use App\Domain\Entity\PlanOptions;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface PlanOptionsRepositoryInterface
{
    /**
     * @return PlanOptions[]
     */
    public function getByUserId(Id $userId): array;

    /**
     * @throws NotFoundException
     */
    public function get(Id $planId, Id $userId): PlanOptions;

    public function delete(PlanOptions $item): void;

    /**
     * @param PlanOptions[] $items
     */
    public function save(array $items): void;
}
