<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\DeleteFolderV1RequestDto;
use App\Application\Account\Dto\DeleteFolderV1ResultDto;

class DeleteFolderV1ResultAssembler
{
    public function assemble(
        DeleteFolderV1RequestDto $dto
    ): DeleteFolderV1ResultDto {
        return new DeleteFolderV1ResultDto();
    }
}
