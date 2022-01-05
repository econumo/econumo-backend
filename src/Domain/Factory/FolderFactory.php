<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class FolderFactory implements FolderFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;
    private FolderRepositoryInterface $folderRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        FolderRepositoryInterface $folderRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->datetimeService = $datetimeService;
        $this->folderRepository = $folderRepository;
        $this->userRepository = $userRepository;
    }

    public function create(Id $userId, FolderName $name): Folder
    {
        return new Folder(
            $this->folderRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
