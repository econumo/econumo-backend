<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreatePlanV1RequestDto;
use App\Application\Budget\Dto\CreatePlanV1ResultDto;
use App\Application\Budget\Assembler\CreatePlanV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Service\Budget\PlanServiceInterface;
use App\Application\Budget\Dto\DeletePlanV1RequestDto;
use App\Application\Budget\Dto\DeletePlanV1ResultDto;
use App\Application\Budget\Assembler\DeletePlanV1ResultAssembler;

readonly class PlanService
{
    public function __construct(
        private CreatePlanV1ResultAssembler $createPlanV1ResultAssembler,
        private PlanServiceInterface $planService,
        private DeletePlanV1ResultAssembler $deletePlanV1ResultAssembler,
    )
    {
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
        $this->planService->deletePlan($userId, new Id($dto->id));
        return $this->deletePlanV1ResultAssembler->assemble($dto);
    }
}
