<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Dto\GetFolderListV1RequestDto;
use App\Application\Account\Dto\GetFolderListV1ResultDto;
use App\Application\Account\Assembler\GetFolderListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;

class FolderListService
{
    private GetFolderListV1ResultAssembler $getFolderListV1ResultAssembler;
    private FolderRepositoryInterface $folderRepository;

    public function __construct(
        GetFolderListV1ResultAssembler $getFolderListV1ResultAssembler,
        FolderRepositoryInterface $folderRepository
    ) {
        $this->getFolderListV1ResultAssembler = $getFolderListV1ResultAssembler;
        $this->folderRepository = $folderRepository;
    }

    public function getFolderList(
        GetFolderListV1RequestDto $dto,
        Id $userId
    ): GetFolderListV1ResultDto {
        $folders = $this->folderRepository->findByUserId($userId);
        return $this->getFolderListV1ResultAssembler->assemble($dto, $folders);
    }
}
