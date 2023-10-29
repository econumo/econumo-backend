<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\CopyEnvelopePlanV1RequestDto;
use App\Application\Budget\Dto\CopyEnvelopePlanV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PlanDataDto;
use DateTimeInterface;

readonly class CopyEnvelopePlanV1ResultAssembler
{
    public function __construct(
        private PlanDataToResultDtoAssembler $planDataToResultDtoAssembler
    ) {
    }

    public function assemble(
        CopyEnvelopePlanV1RequestDto $dto,
        PlanDataDto $planDataDto
    ): CopyEnvelopePlanV1ResultDto {
        $result = new CopyEnvelopePlanV1ResultDto();
        $result->item = $this->planDataToResultDtoAssembler->assemble($planDataDto);

        return $result;
    }
}
