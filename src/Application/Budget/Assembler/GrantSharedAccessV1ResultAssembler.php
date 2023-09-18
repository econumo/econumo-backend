<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GrantSharedAccessV1RequestDto;
use App\Application\Budget\Dto\GrantSharedAccessV1ResultDto;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;

readonly class GrantSharedAccessV1ResultAssembler
{
    public function __construct(private PlanToDtoV1ResultAssembler $planToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        GrantSharedAccessV1RequestDto $dto,
        Plan $plan,
        Id $userId
    ): GrantSharedAccessV1ResultDto {
        $result = new GrantSharedAccessV1ResultDto();
        $result->item = $this->planToDtoV1ResultAssembler->assemble($plan, $userId);

        return $result;
    }
}
