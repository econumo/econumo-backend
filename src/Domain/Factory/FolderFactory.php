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
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly FolderRepositoryInterface $folderRepository, private readonly UserRepositoryInterface $userRepository)
    {
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
