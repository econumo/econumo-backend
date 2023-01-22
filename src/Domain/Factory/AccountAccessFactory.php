<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\AccountAccess;
use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class AccountAccessFactory implements AccountAccessFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    private AccountRepositoryInterface $accountRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        AccountRepositoryInterface $accountRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->datetimeService = $datetimeService;
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
    }

    public function create(Id $accountId, Id $userId, AccountUserRole $role): AccountAccess
    {
        return new AccountAccess(
            $this->accountRepository->getReference($accountId),
            $this->userRepository->getReference($userId),
            $role,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
