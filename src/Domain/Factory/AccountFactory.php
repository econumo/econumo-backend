<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\AccountName;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class AccountFactory implements AccountFactoryInterface
{
    private AccountRepositoryInterface $accountRepository;

    private DatetimeServiceInterface $datetimeService;

    private CurrencyRepositoryInterface $currencyRepository;

    private UserRepositoryInterface $userRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        DatetimeServiceInterface $datetimeService,
        CurrencyRepositoryInterface $currencyRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->datetimeService = $datetimeService;
        $this->currencyRepository = $currencyRepository;
        $this->userRepository = $userRepository;
    }

    public function create(
        Id $userId,
        AccountName $name,
        AccountType $accountType,
        Id $currencyId,
        float $balance,
        Icon $icon
    ): Account {
        return new Account(
            $this->accountRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $this->currencyRepository->getReference($currencyId),
            $balance,
            $accountType,
            $icon,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
