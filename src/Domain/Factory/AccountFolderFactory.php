<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\FolderAccount;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class AccountFolderFactory implements FolderAccountFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;
    private FolderRepositoryInterface $folderRepository;

    public function __construct(DatetimeServiceInterface $datetimeService, FolderRepositoryInterface $folderRepository)
    {
        $this->datetimeService = $datetimeService;
        $this->folderRepository = $folderRepository;
    }

    public function create(Id $folderId, Id $accountId): FolderAccount
    {
        return new FolderAccount(
            $this->folderRepository->getNextIdentity(),
            $folderId,
            $accountId,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
