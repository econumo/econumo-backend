<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\DeleteEnvelopeV1RequestDto;
use App\Application\Budget\Dto\DeleteEnvelopeV1ResultDto;

readonly class DeleteEnvelopeV1ResultAssembler
{
    public function assemble(
        DeleteEnvelopeV1RequestDto $dto
    ): DeleteEnvelopeV1ResultDto {
        return new DeleteEnvelopeV1ResultDto();
    }
}
