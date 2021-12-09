<?php

declare(strict_types=1);

namespace App\Application\Account;

use App\Application\Account\Dto\DeleteFolderV1RequestDto;
use App\Application\Account\Dto\DeleteFolderV1ResultDto;
use App\Application\Account\Assembler\DeleteFolderV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\ForeignFolderRemoveException;
use App\Domain\Exception\TheOnlyFolderRemoveException;
use App\Domain\Service\FolderServiceInterface;

class FolderService
{
    private DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler;
    private FolderServiceInterface $folderService;

    public function __construct(
        DeleteFolderV1ResultAssembler $deleteFolderV1ResultAssembler,
        FolderServiceInterface $folderService
    ) {
        $this->deleteFolderV1ResultAssembler = $deleteFolderV1ResultAssembler;
        $this->folderService = $folderService;
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
            throw new ValidationException('Can not delete foreign folder');
        }

        return $this->deleteFolderV1ResultAssembler->assemble($dto);
    }
}
