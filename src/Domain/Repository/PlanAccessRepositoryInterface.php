<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\PlanAccess;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface PlanAccessRepositoryInterface
{
    /**
     * @return PlanAccess[]
     */
    public function getByPlanId(Id $planId): array;

    /**
     * @param PlanAccess[] $items
     */
    public function save(array $items): void;

    /**
     * @throws NotFoundException
     */
    public function get(Id $planId, Id $userId): PlanAccess;

    public function delete(PlanAccess $item): void;

    /**
     * @return PlanAccess[]
     */
    public function getOwnedByUser(Id $userId): array;

    /**
     * @return PlanAccess[]
     */
    public function getReceivedAccess(Id $userId): array;

    /**
     * @return PlanAccess[]
     */
    public function getIssuedAccess(Id $userId): array;
}
