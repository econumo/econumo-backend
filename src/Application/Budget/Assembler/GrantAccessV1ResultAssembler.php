<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GrantAccessV1RequestDto;
use App\Application\Budget\Dto\GrantAccessV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class GrantAccessV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        GrantAccessV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): GrantAccessV1ResultDto {
        $result = new GrantAccessV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
