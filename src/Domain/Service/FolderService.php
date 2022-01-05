<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\FolderAlreadyExistsException;
use App\Domain\Exception\ForeignFolderRemoveException;
use App\Domain\Exception\TheOnlyFolderRemoveException;
use App\Domain\Factory\FolderFactoryInterface;
use App\Domain\Repository\FolderRepositoryInterface;

final class FolderService implements FolderServiceInterface
{
    private FolderRepositoryInterface $folderRepository;
    private FolderFactoryInterface $folderFactory;

    public function __construct(FolderRepositoryInterface $folderRepository, FolderFactoryInterface $folderFactory)
    {
        $this->folderRepository = $folderRepository;
        $this->folderFactory = $folderFactory;
    }

    public function create(Id $userId, FolderName $name): Folder
    {
        $userFolders = $this->folderRepository->getByUserId($userId);
        foreach ($userFolders as $userFolder) {
            if ($userFolder->getName()->isEqual($name)) {
                throw new FolderAlreadyExistsException();
            }
        }

        $folder = $this->folderFactory->create($userId, $name);
        $this->folderRepository->save($folder);

        return $folder;
    }

    public function delete(Id $userId, Id $folderId): void
    {
        $folder = $this->folderRepository->get($folderId);
        if (!$folder->belongsTo($userId)) {
            throw new ForeignFolderRemoveException();
        }
        if (!$this->folderRepository->isUserHasMoreThanOneFolder($userId)) {
            throw new TheOnlyFolderRemoveException();
        }
        $this->folderRepository->delete($folder);
    }
}
