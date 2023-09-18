<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Service\Dto\PositionDto;

interface PlanServiceInterface
{
    public function createPlan(Id $userId, PlanName $name): Plan;

    /**
     * @param Id $userId
     * @param PositionDto[] $changes
     * @return void
     */
    public function orderPlans(Id $userId, array $changes): void;

    public function deletePlan(Id $userId, Id $planId): void;

    public function updatePlan(Id $planId, PlanName $name): Plan;

    public function revokeSharedAccess(Id $planId, Id $sharedUserId): void;

    public function grantSharedAccess(Id $planId, Id $sharedUserId, UserRole $role): void;
}
