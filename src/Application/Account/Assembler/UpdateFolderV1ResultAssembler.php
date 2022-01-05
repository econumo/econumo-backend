<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\UpdateFolderV1RequestDto;
use App\Application\Account\Dto\UpdateFolderV1ResultDto;

class UpdateFolderV1ResultAssembler
{
    public function assemble(
        UpdateFolderV1RequestDto $dto
    ): UpdateFolderV1ResultDto {
        return new UpdateFolderV1ResultDto();
    }
}
