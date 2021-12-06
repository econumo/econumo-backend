<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;

final class FolderService implements FolderServiceInterface
{
    private FolderRepositoryInterface $folderRepository;

    public function __construct(FolderRepositoryInterface $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    public function userHaveTheOnlyFolder(Id $userId): bool
    {
        return 1 === count($this->folderRepository->getByUserId($userId));
    }

    public function delete(Id $folderId): void
    {
        $this->folderRepository->delete($folderId);
    }
}
