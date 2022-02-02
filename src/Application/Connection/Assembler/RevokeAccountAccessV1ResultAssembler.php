<?php

declare(strict_types=1);

namespace App\Application\Connection\Assembler;

use App\Application\Connection\Dto\RevokeAccountAccessV1RequestDto;
use App\Application\Connection\Dto\RevokeAccountAccessV1ResultDto;

class RevokeAccountAccessV1ResultAssembler
{
    public function assemble(
        RevokeAccountAccessV1RequestDto $dto
    ): RevokeAccountAccessV1ResultDto {
        return new RevokeAccountAccessV1ResultDto();
    }
}
