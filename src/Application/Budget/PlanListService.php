<?php

declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Dto\GetPlanListV1RequestDto;
use App\Application\Budget\Dto\GetPlanListV1ResultDto;
use App\Application\Budget\Assembler\GetPlanListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;

class PlanListService
{
    public function __construct(private readonly GetPlanListV1ResultAssembler $getPlanListV1ResultAssembler)
    {
    }

    public function getPlanList(
        GetPlanListV1RequestDto $dto,
        Id $userId
    ): GetPlanListV1ResultDto {
        // some actions ...
        return $this->getPlanListV1ResultAssembler->assemble($dto);
    }
}
