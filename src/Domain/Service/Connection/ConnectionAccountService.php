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
    public function __construct(private readonly AccountAccessRepositoryInterface $accountAccessRepository, private readonly AccountAccessFactoryInterface $accountAccessFactory, private readonly FolderRepositoryInterface $folderRepository, private readonly AntiCorruptionServiceInterface $antiCorruptionService, private readonly AccountOptionsFactoryInterface $accountOptionsFactory, private readonly AccountOptionsRepositoryInterface $accountOptionsRepository)
    {
    }

    public function revokeAccountAccess(Id $userId, Id $sharedAccountId): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
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

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
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
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            try {
                $accountAccess = $this->accountAccessRepository->get($sharedAccountId, $userId);
            } catch (NotFoundException) {
                $accountAccess = $this->accountAccessFactory->create($sharedAccountId, $userId, $role);

                $accountOptions = $this->accountOptionsRepository->getByUserId($userId);
                $position = 0;
                foreach ($accountOptions as $accountOption) {
                    if ($accountOption->getPosition() > $position) {
                        $position = $accountOption->getPosition();
                    }
                }

                try {
                    $accountOptions = $this->accountOptionsRepository->get($sharedAccountId, $userId);
                    $accountOptions->updatePosition(++$position);
                } catch (NotFoundException) {
                    $accountOptions = $this->accountOptionsFactory->create($sharedAccountId, $userId, ++$position);
                }
                $this->accountOptionsRepository->save([$accountOptions]);

                $folder = $this->folderRepository->getLastFolder($userId);
                $folder->addAccount($accountAccess->getAccount());
                $this->folderRepository->save([$folder]);
            }

            $accountAccess->updateRole($role);
            $this->accountAccessRepository->save([$accountAccess]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (\Throwable $throwable) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $throwable;
        }
    }
}
