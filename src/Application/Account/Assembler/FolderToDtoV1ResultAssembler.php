<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\FolderResultDto;
use App\Domain\Entity\Folder;

class FolderToDtoV1ResultAssembler
{
    public function assemble(Folder $folder): FolderResultDto
    {
        $item = new FolderResultDto();
        $item->id = $folder->getId()->getValue();
        $item->name = $folder->getName();
        $item->position = $folder->getPosition();

        return $item;
    }
}
