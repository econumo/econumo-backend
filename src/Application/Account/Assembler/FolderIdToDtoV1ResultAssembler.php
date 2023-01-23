<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\FolderResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;

class FolderIdToDtoV1ResultAssembler
{
    public function __construct(private readonly FolderRepositoryInterface $folderRepository, private readonly FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler)
    {
    }

    public function assemble(Id $folderId): FolderResultDto
    {
        $folder = $this->folderRepository->get($folderId);
        return $this->folderToDtoV1ResultAssembler->assemble($folder);
    }
}
