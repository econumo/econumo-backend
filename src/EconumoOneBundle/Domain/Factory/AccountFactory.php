<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\AccountType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Factory\AccountFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

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
