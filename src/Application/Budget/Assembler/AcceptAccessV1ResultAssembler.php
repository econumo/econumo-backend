<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\AcceptAccessV1RequestDto;
use App\Application\Budget\Dto\AcceptAccessV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class AcceptAccessV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        AcceptAccessV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): AcceptAccessV1ResultDto {
        $result = new AcceptAccessV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
