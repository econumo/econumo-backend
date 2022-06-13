<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\AccessDeniedException;
use App\Domain\Exception\FolderAlreadyExistsException;
use App\Domain\Exception\LastFolderRemoveException;
use App\Domain\Factory\FolderFactoryInterface;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

final class FolderService implements FolderServiceInterface
{
    private FolderRepositoryInterface $folderRepository;
    private FolderFactoryInterface $folderFactory;
    private AntiCorruptionServiceInterface $antiCorruptionService;

    public function __construct(
        FolderRepositoryInterface $folderRepository,
        FolderFactoryInterface $folderFactory,
        AntiCorruptionServiceInterface $antiCorruptionService
    ) {
        $this->folderRepository = $folderRepository;
        $this->folderFactory = $folderFactory;
        $this->antiCorruptionService = $antiCorruptionService;
    }

    public function create(Id $userId, FolderName $name): Folder
    {
        $userFolders = $this->folderRepository->getByUserId($userId);
        $lastFolderPosition = 0;
        foreach ($userFolders as $userFolder) {
            if ($userFolder->getName()->isEqual($name)) {
                throw new FolderAlreadyExistsException();
            }
            if ($userFolder->getPosition() > $lastFolderPosition) {
                $lastFolderPosition = $userFolder->getPosition();
            }
        }

        $folder = $this->folderFactory->create($userId, $name);
        $folder->updatePosition($lastFolderPosition + 1);
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

        $this->antiCorruptionService->beginTransaction();
        try {
            $userId = $folder->getUserId();
            $this->folderRepository->delete($folder);
            $this->resetOrderFolders($userId);
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }
    }

    public function replace(Id $folderId, Id $replaceFolderId): void
    {
        $folder = $this->folderRepository->get($folderId);
        $replaceFolder = $this->folderRepository->get($replaceFolderId);
        if (!$folder->getUserId()->isEqual($replaceFolder->getUserId())) {
            throw new AccessDeniedException();
        }
        $userId = $folder->getUserId();

        $this->antiCorruptionService->beginTransaction();
        try {
            foreach ($folder->getAccounts() as $account) {
                if (!$replaceFolder->containsAccount($account)) {
                    $replaceFolder->addAccount($account);
                }
            }
            $this->folderRepository->delete($folder);
            $this->folderRepository->save($replaceFolder);

            $this->resetOrderFolders($userId);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }
    }

    public function orderFolders(Id $userId, PositionDto ...$changes): void
    {
        $folders = $this->folderRepository->getByUserId($userId);
        $changed = [];
        foreach ($folders as $folder) {
            foreach ($changes as $change) {
                if ($folder->getId()->isEqual($change->getId())) {
                    $folder->updatePosition($change->position);
                    $changed[] = $folder;
                    break;
                }
            }
        }

        if (!count($changed)) {
            return;
        }
        $this->folderRepository->save(...$changed);
    }

    private function resetOrderFolders(Id $userId): void
    {
        $userFolders = $this->folderRepository->getByUserId($userId);
        usort($userFolders, function (Folder $a, Folder $b) {
            if ($a->getPosition() == $b->getPosition()) {
                return 0;
            }
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });
        foreach ($userFolders as $i => $userFolder) {
            $userFolder->updatePosition($i);
        }
        $this->folderRepository->save(...$userFolders);
    }

    public function hide(Id $folderId): void
    {
        $folder = $this->folderRepository->get($folderId);
        $folder->makeInvisible();
        $this->folderRepository->save($folder);
    }

    public function show(Id $folderId): void
    {
        $folder = $this->folderRepository->get($folderId);
        $folder->makeVisible();
        $this->folderRepository->save($folder);
    }

    public function getChanged(Id $userId, DateTimeInterface $lastUpdate): array
    {
        $folders = $this->folderRepository->getByUserId($userId);
        $result = [];
        foreach ($folders as $folder) {
            if ($folder->getUpdatedAt() > $lastUpdate) {
                $result[] = $folder;
            }
        }

        return $result;
    }
}
