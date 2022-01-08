<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\CreateFolderV1ResultAssembler;
use App\Application\Account\Assembler\DeleteFolderV1ResultAssembler;
use App\Application\Account\Dto\CreateFolderV1RequestDto;
use App\Application\Account\Dto\CreateFolderV1ResultDto;
use App\Application\Account\Dto\DeleteFolderV1RequestDto;
use App\Application\Account\Dto\DeleteFolderV1ResultDto;
use App\Application\Account\Dto\UpdateFolderV1RequestDto;
use App\Application\Account\Dto\UpdateFolderV1ResultDto;
use App\Application\Account\Assembler\UpdateFolderV1ResultAssembler;
use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\LastFolderRemoveException;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\FolderServiceInterface;

class FolderService
{
    private DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler;
    private FolderServiceInterface $folderService;
    private CreateFolderV1ResultAssembler $createFolderV1ResultAssembler;
    private UpdateFolderV1ResultAssembler $updateFolderV1ResultAssembler;
    private FolderRepositoryInterface $folderRepository;

    public function __construct(
        DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler,
        FolderServiceInterface $folderService,
        CreateFolderV1ResultAssembler $createFolderV1ResultAssembler,
        UpdateFolderV1ResultAssembler $updateFolderV1ResultAssembler,
        FolderRepositoryInterface $folderRepository
    ) {
        $this->deleteFolderV1ResultAssembler = $deleteFolderV1ResultAssembler;
        $this->folderService = $folderService;
        $this->createFolderV1ResultAssembler = $createFolderV1ResultAssembler;
        $this->updateFolderV1ResultAssembler = $updateFolderV1ResultAssembler;
        $this->folderRepository = $folderRepository;
    }

    public function deleteFolder(
        DeleteFolderV1RequestDto $dto,
        Id $userId
    ): DeleteFolderV1ResultDto {
        $folder = $this->folderRepository->get(new Id($dto->id));
        if (!$folder->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        try {
            $this->folderService->delete($folder->getId());
        } catch (LastFolderRemoveException $e) {
            throw new ValidationException('Can not delete the only folder');
        }
        return $this->deleteFolderV1ResultAssembler->assemble($dto);
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
}
