<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\PlanFolderResultDto;
use App\Domain\Entity\PlanFolder;
use App\Domain\Entity\ValueObject\Id;

readonly class FolderToDtoV1ResultAssembler
{
    public function assemble(
        PlanFolder $folder
    ): PlanFolderResultDto {
        $item = new PlanFolderResultDto();
        $item->id = $folder->getId()->getValue();
        $item->name = $folder->getName()->getValue();
        $item->position = $folder->getPosition();

        return $item;
    }
}
