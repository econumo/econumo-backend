<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Dto\DeleteAccountV1RequestDto;
use App\EconumoOneBundle\Application\Account\Dto\DeleteAccountV1ResultDto;

class DeleteAccountV1ResultAssembler
{
    public function assemble(
        DeleteAccountV1RequestDto $dto
    ): DeleteAccountV1ResultDto {
        return new DeleteAccountV1ResultDto();
    }
}
