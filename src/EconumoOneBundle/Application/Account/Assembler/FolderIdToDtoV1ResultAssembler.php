<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Assembler;

use App\EconumoOneBundle\Application\Account\Assembler\FolderToDtoV1ResultAssembler;
use App\EconumoOneBundle\Application\Account\Dto\FolderResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;

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
