<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\FolderAlreadyExistsException;
use App\Domain\Exception\LastFolderRemoveException;
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

    public function update(Id $folderId, FolderName $name): void
    {
        $folder = $this->folderRepository->get($folderId);
        $userFolders = $this->folderRepository->getByUserId($folder->getUserId());
        foreach ($userFolders as $userFolder) {
            if ($userFolder->getName()->isEqual($name)) {
                throw new FolderAlreadyExistsException();
            }
        }

        $folder->updateName($name);
        $this->folderRepository->save($folder);
    }

    public function delete(Id $folderId): void
    {
        $folder = $this->folderRepository->get($folderId);
        if (!$this->folderRepository->isUserHasMoreThanOneFolder($folder->getUserId())) {
            throw new LastFolderRemoveException();
        }
        $this->folderRepository->delete($folder);
    }
}
