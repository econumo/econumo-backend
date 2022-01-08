<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\UpdateFolderV1RequestDto;
use App\Application\Account\Dto\UpdateFolderV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

class UpdateFolderV1ResultAssembler
{
    private FolderIdToDtoV1ResultAssembler $folderIdToDtoV1ResultAssembler;

    public function __construct(FolderIdToDtoV1ResultAssembler $folderIdToDtoV1ResultAssembler)
    {
        $this->folderIdToDtoV1ResultAssembler = $folderIdToDtoV1ResultAssembler;
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
