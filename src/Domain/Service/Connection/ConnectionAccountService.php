<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;

use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Factory\AccountAccessFactoryInterface;
use App\Domain\Factory\AccountOptionsFactoryInterface;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use App\Domain\Repository\AccountOptionsRepositoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;

class ConnectionAccountService implements ConnectionAccountServiceInterface
{
    private AccountAccessRepositoryInterface $accountAccessRepository;

    private AccountAccessFactoryInterface $accountAccessFactory;

    private FolderRepositoryInterface $folderRepository;

    private AntiCorruptionServiceInterface $antiCorruptionService;

    private AccountOptionsFactoryInterface $accountOptionsFactory;

    private AccountOptionsRepositoryInterface $accountOptionsRepository;

    public function __construct(
        AccountAccessRepositoryInterface $accountAccessRepository,
        AccountAccessFactoryInterface $accountAccessFactory,
        FolderRepositoryInterface $folderRepository,
        AntiCorruptionServiceInterface $antiCorruptionService,
        AccountOptionsFactoryInterface $accountOptionsFactory,
        AccountOptionsRepositoryInterface $accountOptionsRepository
    ) {
        $this->accountAccessRepository = $accountAccessRepository;
        $this->accountAccessFactory = $accountAccessFactory;
        $this->folderRepository = $folderRepository;
        $this->antiCorruptionService = $antiCorruptionService;
        $this->accountOptionsFactory = $accountOptionsFactory;
        $this->accountOptionsRepository = $accountOptionsRepository;
    }

    public function revokeAccountAccess(Id $userId, Id $sharedAccountId): void
    {
        $this->antiCorruptionService->beginTransaction();
        try {
            $accountAccess = $this->accountAccessRepository->get($sharedAccountId, $userId);
            $folders = $this->folderRepository->getByUserId($userId);
            foreach ($folders as $folder) {
                if ($folder->containsAccount($accountAccess->getAccount())) {
                    $folder->removeAccount($accountAccess->getAccount());
                }
            }

            $accountOptions = $this->accountOptionsRepository->get($sharedAccountId, $userId);
            $this->accountOptionsRepository->delete($accountOptions);
            $this->accountAccessRepository->delete($accountAccess);

            $this->antiCorruptionService->commit();
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback();
            throw $throwable;
        }
    }

    /**
     * @inheritDoc
     */
    public function getReceivedAccountAccess(Id $userId): array
    {
        return $this->accountAccessRepository->getReceivedAccess($userId);
    }

    /**
     * @inheritDoc
     */
    public function getIssuedAccountAccess(Id $userId): array
    {
        return $this->accountAccessRepository->getIssuedAccess($userId);
    }

    public function setAccountAccess(Id $userId, Id $sharedAccountId, AccountUserRole $role): void
    {
        $this->antiCorruptionService->beginTransaction();
        try {
            try {
                $accountAccess = $this->accountAccessRepository->get($sharedAccountId, $userId);
            } catch (NotFoundException $notFoundException) {
                $accountAccess = $this->accountAccessFactory->create($sharedAccountId, $userId, $role);

                $accountOptions = $this->accountOptionsRepository->getByUserId($userId);
                $position = 0;
                foreach ($accountOptions as $accountOption) {
                    if ($accountOption->getPosition() > $position) {
                        $position = $accountOption->getPosition();
                    }
                }

                $accountOptions = $this->accountOptionsFactory->create($sharedAccountId, $userId, ++$position);
                $this->accountOptionsRepository->save($accountOptions);

                $folder = $this->folderRepository->getLastFolder($userId);
                $folder->addAccount($accountAccess->getAccount());
                $this->folderRepository->save($folder);
            }

            $accountAccess->updateRole($role);
            $this->accountAccessRepository->save($accountAccess);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback();
            throw $throwable;
        }
    }
}
