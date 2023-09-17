<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\CreatePlanV1RequestDto;
use App\Application\Budget\Dto\CreatePlanV1ResultDto;
use App\Application\Budget\Assembler\CreatePlanV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Service\Budget\PlanServiceInterface;

readonly class PlanService
{
    public function __construct(
        private CreatePlanV1ResultAssembler $createPlanV1ResultAssembler,
        private PlanServiceInterface $planService
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
}
