<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\AccountOptions;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class AccountOptionsFactory implements AccountOptionsFactoryInterface
{
    private AccountRepositoryInterface $accountRepository;

    private UserRepositoryInterface $userRepository;

    private DatetimeServiceInterface $datetimeService;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        UserRepositoryInterface $userRepository,
        DatetimeServiceInterface $datetimeService
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->datetimeService = $datetimeService;
    }

    public function create(
        Id $accountId,
        Id $userId,
        int $position
    ): AccountOptions {
        return new AccountOptions(
            $this->accountRepository->getReference($accountId),
            $this->userRepository->getReference($userId),
            $position,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
