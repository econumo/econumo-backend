<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\CreatePlanV1RequestDto;
use App\Application\Budget\Dto\CreatePlanV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class CreatePlanV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        CreatePlanV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): CreatePlanV1ResultDto {
        $result = new CreatePlanV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
