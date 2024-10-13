<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Assembler;

use App\EconumoOneBundle\Application\Payee\Dto\UnarchivePayeeV1RequestDto;
use App\EconumoOneBundle\Application\Payee\Dto\UnarchivePayeeV1ResultDto;

class UnarchivePayeeV1ResultAssembler
{
    public function assemble(
        UnarchivePayeeV1RequestDto $dto
    ): UnarchivePayeeV1ResultDto {
        return new UnarchivePayeeV1ResultDto();
    }
}
