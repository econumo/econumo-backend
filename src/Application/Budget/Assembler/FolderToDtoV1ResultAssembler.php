<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\FolderResultDto;
use App\Domain\Entity\PlanFolder;
use App\Domain\Entity\ValueObject\Id;

readonly class FolderToDtoV1ResultAssembler
{
    public function assemble(
        PlanFolder $folder,
        Id $userId
    ): FolderResultDto {
        $item = new FolderResultDto();
        $item->id = $folder->getId()->getValue();
        $item->name = $folder->getName()->getValue();
        $item->position = $folder->getPosition();

        return $item;
    }
}
