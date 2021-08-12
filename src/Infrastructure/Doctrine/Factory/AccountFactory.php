<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Domain\Entity\Account;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\AccountFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class AccountFactory implements AccountFactoryInterface
{
    private AccountRepositoryInterface $accountRepository;
    private DatetimeServiceInterface $datetimeService;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        DatetimeServiceInterface $datetimeService
    ) {
        $this->accountRepository = $accountRepository;
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $userId, string $name, AccountType $accountType, Id $currencyId, float $balance): Account
    {
        return new Account(
            $this->accountRepository->getNextIdentity(),
            $userId,
            $name,
            $currencyId,
            $balance,
            $accountType,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
