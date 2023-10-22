<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\UpdateEnvelopePlanV1RequestDto;
use App\Application\Budget\Dto\UpdateEnvelopePlanV1ResultDto;

readonly class UpdateEnvelopePlanV1ResultAssembler
{
    public function assemble(
        UpdateEnvelopePlanV1RequestDto $dto
    ): UpdateEnvelopePlanV1ResultDto {
        return new UpdateEnvelopePlanV1ResultDto();
    }
}
