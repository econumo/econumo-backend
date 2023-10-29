<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\TransferEnvelopePlanV1RequestDto;
use App\Application\Budget\Dto\TransferEnvelopePlanV1ResultDto;

readonly class TransferEnvelopePlanV1ResultAssembler
{
    public function assemble(
        TransferEnvelopePlanV1RequestDto $dto
    ): TransferEnvelopePlanV1ResultDto {
        return new TransferEnvelopePlanV1ResultDto();
    }
}
