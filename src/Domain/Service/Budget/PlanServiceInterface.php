<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Entity\ValueObject\PlanPeriodType;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Service\Dto\PlanDataDto;
use App\Domain\Service\Dto\PlanDto;
use App\Domain\Service\Dto\PositionDto;
use DateTimeImmutable;
use DateTimeInterface;

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

    public function revokeAccess(Id $planId, Id $sharedUserId): void;

    public function grantAccess(Id $planId, Id $sharedUserId, UserRole $role): void;

    public function acceptAccess(Id $planId, Id $userId): void;

    public function getPlan(Id $planId): PlanDto;

    /**
     * @param Id $planId
     * @param PlanPeriodType $periodType
     * @param DateTimeInterface $periodStart
     * @param int $numberOfPeriods
     * @return PlanDataDto[]
     */
    public function getPlanData(Id $planId, PlanPeriodType $periodType, DateTimeInterface $periodStart, int $numberOfPeriods): array;

    public function resetPlan(Id $planId, DateTimeImmutable $periodStart): void;
}
