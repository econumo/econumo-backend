<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Dto\UpdateFolderV1RequestDto;
use App\EconumoOneBundle\Application\Account\Assembler\FolderIdToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Account\Dto\UpdateFolderV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

class UpdateFolderV1ResultAssembler
{
    public function __construct(private readonly FolderIdToDtoV1ResultAssembler $folderIdToDtoV1ResultAssembler)
    {
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
