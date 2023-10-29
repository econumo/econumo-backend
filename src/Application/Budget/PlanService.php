<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreatePlanV1RequestDto;
use App\Application\Budget\Dto\CreatePlanV1ResultDto;
use App\Application\Budget\Assembler\CreatePlanV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Service\Budget\PlanAccessServiceInterface;
use App\Domain\Service\Budget\PlanServiceInterface;
use App\Application\Budget\Dto\DeletePlanV1RequestDto;
use App\Application\Budget\Dto\DeletePlanV1ResultDto;
use App\Application\Budget\Assembler\DeletePlanV1ResultAssembler;
use App\Application\Budget\Dto\UpdatePlanV1RequestDto;
use App\Application\Budget\Dto\UpdatePlanV1ResultDto;
use App\Application\Budget\Assembler\UpdatePlanV1ResultAssembler;
use App\Application\Budget\Dto\GetPlanV1RequestDto;
use App\Application\Budget\Dto\GetPlanV1ResultDto;
use App\Application\Budget\Assembler\GetPlanV1ResultAssembler;
use App\Application\Budget\Dto\ResetPlanV1RequestDto;
use App\Application\Budget\Dto\ResetPlanV1ResultDto;
use App\Application\Budget\Assembler\ResetPlanV1ResultAssembler;
use DateTimeImmutable;

readonly class PlanService
{
    public function __construct(
        private CreatePlanV1ResultAssembler $createPlanV1ResultAssembler,
        private PlanServiceInterface $planService,
        private DeletePlanV1ResultAssembler $deletePlanV1ResultAssembler,
        private UpdatePlanV1ResultAssembler $updatePlanV1ResultAssembler,
        private PlanAccessServiceInterface $planAccessService,
        private GetPlanV1ResultAssembler $getPlanV1ResultAssembler,
        private ResetPlanV1ResultAssembler $resetPlanV1ResultAssembler,
    ) {
    }

    public function createPlan(
        CreatePlanV1RequestDto $dto,
        Id $userId
    ): CreatePlanV1ResultDto {
        $plan = $this->planService->createPlan($userId, new PlanName($dto->name));
        return $this->createPlanV1ResultAssembler->assemble($dto, $plan, $userId);
    }

    public function deletePlan(
        DeletePlanV1RequestDto $dto,
        Id $userId
    ): DeletePlanV1ResultDto {
        $planId = new Id($dto->id);
        if (!$this->planAccessService->canDeletePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }
        $this->planService->deletePlan($userId, $planId);
        return $this->deletePlanV1ResultAssembler->assemble($dto);
    }

    public function updatePlan(
        UpdatePlanV1RequestDto $dto,
        Id $userId
    ): UpdatePlanV1ResultDto {
        $planId = new Id($dto->id);
        if (!$this->planAccessService->canUpdatePlan($userId, $planId)) {
            throw new AccessDeniedException();
        }

        $plan = $this->planService->updatePlan($planId, new PlanName($dto->name));
        return $this->updatePlanV1ResultAssembler->assemble($dto, $plan, $userId);
    }

    public function getPlan(
        GetPlanV1RequestDto $dto,
        Id $userId
    ): GetPlanV1ResultDto {
        $planId = new Id($dto->id);
        if (!$this->planAccessService->canReadPlan($userId, $planId)) {
            throw new AccessDeniedException();
        }

        $plan = $this->planService->getPlan($planId);
        return $this->getPlanV1ResultAssembler->assemble($dto, $plan, $userId);
    }

    public function resetPlan(
        ResetPlanV1RequestDto $dto,
        Id $userId
    ): ResetPlanV1ResultDto {
        $planId = new Id($dto->id);
        if (!$this->planAccessService->canReadPlan($userId, $planId)) {
            throw new AccessDeniedException();
        }

        $periodStart = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->periodStart)->format('Y-m-01 00:00:00')
        );
        $this->planService->resetPlan($planId, $periodStart);
        return $this->resetPlanV1ResultAssembler->assemble($dto);
    }
}
