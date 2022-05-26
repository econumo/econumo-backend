<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\HideFolderV1RequestDto;
use App\Application\Account\Dto\HideFolderV1ResultDto;

class HideFolderV1ResultAssembler
{
    public function assemble(
        HideFolderV1RequestDto $dto
    ): HideFolderV1ResultDto {
        return new HideFolderV1ResultDto();
    }
}
