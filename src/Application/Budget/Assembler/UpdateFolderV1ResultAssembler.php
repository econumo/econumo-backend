<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\UpdateFolderV1RequestDto;
use App\Application\Budget\Dto\UpdateFolderV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

readonly class UpdateFolderV1ResultAssembler
{
    public function __construct(
        private FolderIdToDtoV1ResultAssembler $folderIdToDtoV1ResultAssembler
    ) {
    }

    public function assemble(
        UpdateFolderV1RequestDto $dto,
        Id $folderId
    ): UpdateFolderV1ResultDto {
        $result = new UpdateFolderV1ResultDto();
        $result->item = $this->folderIdToDtoV1ResultAssembler->assemble($folderId);

        return $result;
    }
}
