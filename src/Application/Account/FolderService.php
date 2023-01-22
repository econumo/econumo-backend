<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\CreateFolderV1ResultAssembler;
use App\Application\Account\Assembler\HideFolderV1ResultAssembler;
use App\Application\Account\Assembler\ReplaceFolderV1ResultAssembler;
use App\Application\Account\Assembler\UpdateFolderV1ResultAssembler;
use App\Application\Account\Dto\CreateFolderV1RequestDto;
use App\Application\Account\Dto\CreateFolderV1ResultDto;
use App\Application\Account\Dto\HideFolderV1RequestDto;
use App\Application\Account\Dto\HideFolderV1ResultDto;
use App\Application\Account\Dto\ReplaceFolderV1RequestDto;
use App\Application\Account\Dto\ReplaceFolderV1ResultDto;
use App\Application\Account\Dto\ShowFolderV1RequestDto;
use App\Application\Account\Dto\ShowFolderV1ResultDto;
use App\Application\Account\Assembler\ShowFolderV1ResultAssembler;
use App\Application\Account\Dto\UpdateFolderV1RequestDto;
use App\Application\Account\Dto\UpdateFolderV1ResultDto;
use App\Application\Exception\AccessDeniedException;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\FolderServiceInterface;

class FolderService
{
    private FolderServiceInterface $folderService;

    private CreateFolderV1ResultAssembler $createFolderV1ResultAssembler;

    private UpdateFolderV1ResultAssembler $updateFolderV1ResultAssembler;

    private FolderRepositoryInterface $folderRepository;

    private ReplaceFolderV1ResultAssembler $replaceFolderV1ResultAssembler;

    private HideFolderV1ResultAssembler $hideFolderV1ResultAssembler;

    private ShowFolderV1ResultAssembler $showFolderV1ResultAssembler;

    public function __construct(
        FolderServiceInterface $folderService,
        CreateFolderV1ResultAssembler $createFolderV1ResultAssembler,
        UpdateFolderV1ResultAssembler $updateFolderV1ResultAssembler,
        FolderRepositoryInterface $folderRepository,
        ReplaceFolderV1ResultAssembler $replaceFolderV1ResultAssembler,
        HideFolderV1ResultAssembler $hideFolderV1ResultAssembler,
        ShowFolderV1ResultAssembler $showFolderV1ResultAssembler
    ) {
        $this->folderService = $folderService;
        $this->createFolderV1ResultAssembler = $createFolderV1ResultAssembler;
        $this->updateFolderV1ResultAssembler = $updateFolderV1ResultAssembler;
        $this->folderRepository = $folderRepository;
        $this->replaceFolderV1ResultAssembler = $replaceFolderV1ResultAssembler;
        $this->hideFolderV1ResultAssembler = $hideFolderV1ResultAssembler;
        $this->showFolderV1ResultAssembler = $showFolderV1ResultAssembler;
    }

    public function createFolder(
        CreateFolderV1RequestDto $dto,
        Id $userId
    ): CreateFolderV1ResultDto {
        $folder = $this->folderService->create($userId, new FolderName($dto->name));
        return $this->createFolderV1ResultAssembler->assemble($dto, $folder);
    }

    public function updateFolder(
        UpdateFolderV1RequestDto $dto,
        Id $userId
    ): UpdateFolderV1ResultDto {
        $folder = $this->folderRepository->get(new Id($dto->id));
        if (!$folder->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->folderService->update($folder->getId(), new FolderName($dto->name));
        return $this->updateFolderV1ResultAssembler->assemble($dto, $folder->getId());
    }

    public function replaceFolder(
        ReplaceFolderV1RequestDto $dto,
        Id $userId
    ): ReplaceFolderV1ResultDto {
        $folder = $this->folderRepository->get(new Id($dto->id));
        if (!$folder->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->folderService->replace($folder->getId(), new Id($dto->replaceId));
        return $this->replaceFolderV1ResultAssembler->assemble($dto);
    }

    public function hideFolder(
        HideFolderV1RequestDto $dto,
        Id $userId
    ): HideFolderV1ResultDto {
        $folder = $this->folderRepository->get(new Id($dto->id));
        if (!$folder->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->folderService->hide($folder->getId());
        return $this->hideFolderV1ResultAssembler->assemble($dto);
    }

    public function showFolder(
        ShowFolderV1RequestDto $dto,
        Id $userId
    ): ShowFolderV1ResultDto {
        $folder = $this->folderRepository->get(new Id($dto->id));
        if (!$folder->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->folderService->show($folder->getId());
        return $this->showFolderV1ResultAssembler->assemble($dto);
    }
}
