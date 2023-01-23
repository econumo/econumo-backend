<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\GetFolderListV1RequestDto;
use App\Application\Account\Dto\GetFolderListV1ResultDto;
use App\Domain\Entity\Folder;

class GetFolderListV1ResultAssembler
{
    public function __construct(private readonly FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler)
    {
    }

    /**
     * @param Folder[] $folders
     */
    public function assemble(
        GetFolderListV1RequestDto $dto,
        array $folders
    ): GetFolderListV1ResultDto {
        $result = new GetFolderListV1ResultDto();
        $result->items = [];
        foreach ($folders as $item) {
            $result->items[] = $this->folderToDtoV1ResultAssembler->assemble($item);
        }

        return $result;
    }
}
