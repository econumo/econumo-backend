<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\ResetPlanV1RequestDto;
use App\Application\Budget\Dto\ResetPlanV1ResultDto;

readonly class ResetPlanV1ResultAssembler
{
    public function assemble(
        ResetPlanV1RequestDto $dto
    ): ResetPlanV1ResultDto {
        return new ResetPlanV1ResultDto();
    }
}
