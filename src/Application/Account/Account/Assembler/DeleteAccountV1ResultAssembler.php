<?php

declare(strict_types=1);

namespace App\Application\Account\Account\Assembler;

use App\Application\Account\Account\Dto\DeleteAccountV1RequestDto;
use App\Application\Account\Account\Dto\DeleteAccountV1ResultDto;

class DeleteAccountV1ResultAssembler
{
    public function assemble(
        DeleteAccountV1RequestDto $dto
    ): DeleteAccountV1ResultDto {
        return new DeleteAccountV1ResultDto();
    }
}
