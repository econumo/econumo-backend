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
    public function __construct(private readonly AccountRepositoryInterface $accountRepository, private readonly DatetimeServiceInterface $datetimeService, private readonly CurrencyRepositoryInterface $currencyRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function create(
        Id $userId,
        AccountName $name,
        AccountType $accountType,
        Id $currencyId,
        Icon $icon
    ): Account {
        return new Account(
            $this->accountRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $this->currencyRepository->getReference($currencyId),
            $accountType,
            $icon,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
