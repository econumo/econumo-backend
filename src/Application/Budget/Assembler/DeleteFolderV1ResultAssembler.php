<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\DeleteFolderV1RequestDto;
use App\Application\Budget\Dto\DeleteFolderV1ResultDto;

class DeleteFolderV1ResultAssembler
{
    public function assemble(
        DeleteFolderV1RequestDto $dto
    ): DeleteFolderV1ResultDto {
        return new DeleteFolderV1ResultDto();
    }
}
