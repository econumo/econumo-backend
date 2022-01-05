<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\CreateFolderV1RequestDto;
use App\Application\Account\Dto\CreateFolderV1ResultDto;
use App\Domain\Entity\Folder;

class CreateFolderV1ResultAssembler
{
    private FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler;

    public function __construct(FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler)
    {
        $this->folderToDtoV1ResultAssembler = $folderToDtoV1ResultAssembler;
    }

    public function assemble(
        CreateFolderV1RequestDto $dto,
        Folder $folder
    ): CreateFolderV1ResultDto {
        $result = new CreateFolderV1ResultDto();
        $result->item = $this->folderToDtoV1ResultAssembler->assemble($folder);

        return $result;
    }
}
