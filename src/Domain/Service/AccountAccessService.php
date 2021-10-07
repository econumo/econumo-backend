<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\AccountAccessRepositoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;

class AccountAccessService implements AccountAccessServiceInterface
{
    private AccountAccessRepositoryInterface $accountAccessRepository;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        AccountAccessRepositoryInterface $accountAccessRepository,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->accountAccessRepository = $accountAccessRepository;
        $this->accountRepository = $accountRepository;
    }

    public function isAccessAllowed(Id $userId, Id $accountId): bool
    {
        $account = $this->accountRepository->get($accountId);
        if ($account->getUserId()->isEqual($userId)) {
            return true;
        }

        try {
            $this->accountAccessRepository->get($accountId, $userId);
        } catch (NotFoundException $exception) {
            return false;
        }

        return true;
    }

    public function canDeleteAccount(Id $userId, Id $accountId): bool
    {
        return $this->canUpdateAccount($userId, $accountId);
    }

    public function canUpdateAccount(Id $userId, Id $accountId): bool
    {
        $account = $this->accountRepository->get($accountId);
        if ($account->getUserId()->isEqual($userId)) {
            return true;
        }

        try {
            $access = $this->accountAccessRepository->get($accountId, $userId);
        } catch (NotFoundException $exception) {
            return false;
        }

        return $access->getRole()->isAdmin();
    }

    public function canViewTransactions(Id $userId, Id $accountId): bool
    {
        return $this->isAccessAllowed($userId, $accountId);
    }

    public function canAddTransaction(Id $userId, Id $accountId): bool
    {
        $account = $this->accountRepository->get($accountId);
        if ($account->getUserId()->isEqual($userId)) {
            return true;
        }

        try {
            $access = $this->accountAccessRepository->get($accountId, $userId);
        } catch (NotFoundException $exception) {
            return false;
        }

        return $access->getRole()->isAdmin() || $access->getRole()->isUser();
    }

    public function canUpdateTransaction(Id $userId, Id $accountId): bool
    {
        return $this->canAddTransaction($userId, $accountId);
    }

    public function canDeleteTransaction(Id $userId, Id $accountId): bool
    {
        return $this->canAddTransaction($userId, $accountId);
    }
}
