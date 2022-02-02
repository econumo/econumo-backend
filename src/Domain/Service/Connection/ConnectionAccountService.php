<?php

declare(strict_types=1);


namespace App\Domain\Service\Connection;

use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Factory\AccountAccessFactoryInterface;
use App\Domain\Repository\AccountAccessRepositoryInterface;

class ConnectionAccountService implements ConnectionAccountServiceInterface
{
    private AccountAccessRepositoryInterface $accountAccessRepository;
    private AccountAccessFactoryInterface $accountAccessFactory;

    public function __construct(
        AccountAccessRepositoryInterface $accountAccessRepository,
        AccountAccessFactoryInterface $accountAccessFactory
    ) {
        $this->accountAccessRepository = $accountAccessRepository;
        $this->accountAccessFactory = $accountAccessFactory;
    }

    public function revokeAccountAccess(Id $userId, Id $sharedAccountId): void
    {
        $this->accountAccessRepository->delete($sharedAccountId, $userId);
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
        try {
            $accountAccess = $this->accountAccessRepository->get($sharedAccountId, $userId);
        } catch (NotFoundException $exception) {
            $accountAccess = $this->accountAccessFactory->create($sharedAccountId, $userId, $role);
        }

        $accountAccess->updateRole($role);
        $this->accountAccessRepository->save($accountAccess);
    }
}
