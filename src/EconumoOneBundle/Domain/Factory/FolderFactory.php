<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Folder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\FolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\FolderFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\FolderRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

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
