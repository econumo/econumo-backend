<?php

declare(strict_types=1);

namespace App\Application\Payee\Assembler;

use App\Application\Payee\Dto\ArchivePayeeV1RequestDto;
use App\Application\Payee\Dto\ArchivePayeeV1ResultDto;

class ArchivePayeeV1ResultAssembler
{
    public function assemble(
        ArchivePayeeV1RequestDto $dto
    ): ArchivePayeeV1ResultDto {
        return new ArchivePayeeV1ResultDto();
    }
}
