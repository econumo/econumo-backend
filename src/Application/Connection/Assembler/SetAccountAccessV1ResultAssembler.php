<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\SetAccountAccessV1RequestDto;
use App\Application\Connection\Dto\SetAccountAccessV1ResultDto;

class SetAccountAccessV1ResultAssembler
{
    public function assemble(
        SetAccountAccessV1RequestDto $dto
    ): SetAccountAccessV1ResultDto {
        return new SetAccountAccessV1ResultDto();
    }
}
