<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\PlanFolderResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\PlanFolderRepositoryInterface;

readonly class FolderIdToDtoV1ResultAssembler
{
    public function __construct(
        private PlanFolderRepositoryInterface $planFolderRepository,
        private FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler
    ) {
    }

    public function assemble(
        Id $folderId
    ): PlanFolderResultDto {
        $folder = $this->planFolderRepository->get($folderId);
        return $this->folderToDtoV1ResultAssembler->assemble($folder);
    }
}
