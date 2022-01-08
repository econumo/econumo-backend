<?php

declare(strict_types=1);

namespace App\Application\Account\Assembler;

use App\Application\Account\Dto\OrderFolderListV1RequestDto;
use App\Application\Account\Dto\OrderFolderListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;

class OrderFolderListV1ResultAssembler
{
    private FolderRepositoryInterface $folderRepository;
    private FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler;

    public function __construct(FolderRepositoryInterface $folderRepository, FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler)
    {
        $this->folderRepository = $folderRepository;
        $this->folderToDtoV1ResultAssembler = $folderToDtoV1ResultAssembler;
    }

    public function assemble(
        OrderFolderListV1RequestDto $dto,
        Id $userId
    ): OrderFolderListV1ResultDto {
        $result = new OrderFolderListV1ResultDto();
        $folders = $this->folderRepository->getByUserId($userId);
        $result->items = [];
        foreach ($folders as $folder) {
            $result->items[] = $this->folderToDtoV1ResultAssembler->assemble($folder);
        }

        return $result;
    }
}
