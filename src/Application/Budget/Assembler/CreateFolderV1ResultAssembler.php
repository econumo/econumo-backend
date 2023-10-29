<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\CreateFolderV1RequestDto;
use App\Application\Budget\Dto\CreateFolderV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

readonly class CreateFolderV1ResultAssembler
{
    public function __construct(
        private FolderIdToDtoV1ResultAssembler $folderIdToDtoV1ResultAssembler
    ) {
    }

    public function assemble(
        CreateFolderV1RequestDto $dto,
        Id $folderId
    ): CreateFolderV1ResultDto {
        $result = new CreateFolderV1ResultDto();
        $result->item = $this->folderIdToDtoV1ResultAssembler->assemble($folderId);

        return $result;
    }
}
