<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\RevokeAccessV1RequestDto;
use App\Application\Budget\Dto\RevokeAccessV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class RevokeAccessV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        RevokeAccessV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): RevokeAccessV1ResultDto {
        $result = new RevokeAccessV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
