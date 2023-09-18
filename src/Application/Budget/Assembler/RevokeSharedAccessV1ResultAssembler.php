<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\RevokeSharedAccessV1RequestDto;
use App\Application\Budget\Dto\RevokeSharedAccessV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class RevokeSharedAccessV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        RevokeSharedAccessV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): RevokeSharedAccessV1ResultDto {
        $result = new RevokeSharedAccessV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
