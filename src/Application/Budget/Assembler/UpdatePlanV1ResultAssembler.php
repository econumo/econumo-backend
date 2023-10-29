<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\UpdatePlanV1RequestDto;
use App\Application\Budget\Dto\UpdatePlanV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class UpdatePlanV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        UpdatePlanV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): UpdatePlanV1ResultDto {
        $result = new UpdatePlanV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
