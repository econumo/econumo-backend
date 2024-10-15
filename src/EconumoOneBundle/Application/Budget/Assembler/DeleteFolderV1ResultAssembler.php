<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\DeleteFolderV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteFolderV1ResultDto;

readonly class DeleteFolderV1ResultAssembler
{
    public function assemble(
        DeleteFolderV1RequestDto $dto
    ): DeleteFolderV1ResultDto {
        return new DeleteFolderV1ResultDto();
    }
}
