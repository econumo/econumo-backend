<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetPlanListV1RequestDto;
use App\Application\Budget\Dto\GetPlanListV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

class GetPlanListV1ResultAssembler
{
    public function __construct(private readonly PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    /**
     * @param GetPlanListV1RequestDto $dto
     * @param Plan[] $plans
     * @return GetPlanListV1ResultDto
     */
    public function assemble(
        GetPlanListV1RequestDto $dto,
        array $plans,
        Id $userId
    ): GetPlanListV1ResultDto {
        $result = new GetPlanListV1ResultDto();
        $result->items = [];
        foreach ($plans as $plan) {
            $result->items[] = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);
        }

        return $result;
    }
}
