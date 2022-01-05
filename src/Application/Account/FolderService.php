<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Assembler\DeleteFolderV1ResultAssembler;
use App\Application\Account\Dto\CreateFolderV1RequestDto;
use App\Application\Account\Dto\CreateFolderV1ResultDto;
use App\Application\Account\Assembler\CreateFolderV1ResultAssembler;
use App\Application\Account\Dto\DeleteFolderV1RequestDto;
use App\Application\Account\Dto\DeleteFolderV1ResultDto;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\ForeignFolderRemoveException;
use App\Domain\Exception\TheOnlyFolderRemoveException;
use App\Domain\Service\FolderServiceInterface;

class FolderService
{
    private DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler;
    private FolderServiceInterface $folderService;
    private CreateFolderV1ResultAssembler $createFolderV1ResultAssembler;

    public function __construct(
        DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler,
        FolderServiceInterface $folderService,
        CreateFolderV1ResultAssembler $createFolderV1ResultAssembler
    ) {
        $this->deleteFolderV1ResultAssembler = $deleteFolderV1ResultAssembler;
        $this->folderService = $folderService;
        $this->createFolderV1ResultAssembler = $createFolderV1ResultAssembler;
    }

    public function deleteFolder(
        DeleteFolderV1RequestDto $dto,
        Id $userId
    ): DeleteFolderV1ResultDto {
        $folderId = new Id($dto->id);
        try {
            $this->folderService->delete($userId, $folderId);
        } catch (TheOnlyFolderRemoveException $e) {
            throw new ValidationException('Can not delete the only folder');
        } catch (ForeignFolderRemoveException $e) {
            throw new ValidationException('You don\'t have that folder');
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
}
