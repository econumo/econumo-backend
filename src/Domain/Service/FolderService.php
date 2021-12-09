<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\ForeignFolderRemoveException;
use App\Domain\Exception\TheOnlyFolderRemoveException;
use App\Domain\Repository\FolderRepositoryInterface;

final class FolderService implements FolderServiceInterface
{
    private FolderRepositoryInterface $folderRepository;

    public function __construct(FolderRepositoryInterface $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    private function user(Id $userId): bool
    {
        return count($this->folderRepository->getByUserId($userId)) > 1;
    }

    public function delete(Id $userId, Id $folderId): void
    {
        if (!$this->folderRepository->isUserHasFolder($userId, $folderId)) {
            throw new ForeignFolderRemoveException();
        }
        if (!$this->folderRepository->isUserHasMoreThanOneFolder($userId)) {
            throw new TheOnlyFolderRemoveException();
        }
        $this->folderRepository->delete($folderId);
    }
}
