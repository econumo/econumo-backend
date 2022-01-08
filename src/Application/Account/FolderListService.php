<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\GetFolderListV1ResultAssembler;
use App\Application\Account\Dto\GetFolderListV1RequestDto;
use App\Application\Account\Dto\GetFolderListV1ResultDto;
use App\Application\Account\Dto\OrderFolderListV1RequestDto;
use App\Application\Account\Dto\OrderFolderListV1ResultDto;
use App\Application\Account\Assembler\OrderFolderListV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\FolderServiceInterface;

class FolderListService
{
    private GetFolderListV1ResultAssembler $getFolderListV1ResultAssembler;
    private FolderRepositoryInterface $folderRepository;
    private OrderFolderListV1ResultAssembler $orderFolderListV1ResultAssembler;
    private FolderServiceInterface $folderService;

    public function __construct(
        GetFolderListV1ResultAssembler $getFolderListV1ResultAssembler,
        FolderRepositoryInterface $folderRepository,
        OrderFolderListV1ResultAssembler $orderFolderListV1ResultAssembler,
        FolderServiceInterface $folderService
    ) {
        $this->getFolderListV1ResultAssembler = $getFolderListV1ResultAssembler;
        $this->folderRepository = $folderRepository;
        $this->orderFolderListV1ResultAssembler = $orderFolderListV1ResultAssembler;
        $this->folderService = $folderService;
    }

    public function getFolderList(
        GetFolderListV1RequestDto $dto,
        Id $userId
    ): GetFolderListV1ResultDto {
        $folders = $this->folderRepository->getByUserId($userId);
        return $this->getFolderListV1ResultAssembler->assemble($dto, $folders);
    }

    public function orderFolderList(
        OrderFolderListV1RequestDto $dto,
        Id $userId
    ): OrderFolderListV1ResultDto {
        if (!count($dto->changes)) {
            throw new ValidationException('Folder list is empty');
        }
        $this->folderService->orderFolders($userId, ...$dto->changes);
        return $this->orderFolderListV1ResultAssembler->assemble($dto, $userId);
    }
}
