<?php

declare(strict_types=1);

namespace App\Application\Payee\Assembler;

use App\Application\Payee\Dto\DeletePayeeV1RequestDto;
use App\Application\Payee\Dto\DeletePayeeV1ResultDto;

class DeletePayeeV1ResultAssembler
{
    public function assemble(
        DeletePayeeV1RequestDto $dto
    ): DeletePayeeV1ResultDto {
        return new DeletePayeeV1ResultDto();
    }
}
