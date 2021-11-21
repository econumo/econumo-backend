<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class FolderFactory implements FolderFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;
    private FolderRepositoryInterface $folderRepository;

    public function __construct(DatetimeServiceInterface $datetimeService, FolderRepositoryInterface $folderRepository)
    {
        $this->datetimeService = $datetimeService;
        $this->folderRepository = $folderRepository;
    }

    public function create(Id $userId, string $name): Folder
    {
        return new Folder(
            $this->folderRepository->getNextIdentity(),
            $userId,
            $name,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
