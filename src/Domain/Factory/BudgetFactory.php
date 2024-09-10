<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\BudgetName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use DateTimeInterface;

readonly class BudgetFactory implements BudgetFactoryInterface
{
    public function __construct(
        private DatetimeServiceInterface $datetimeService,
        private AccountRepositoryInterface $accountRepository,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function create(
        Id $userId,
        Id $id,
        BudgetName $name,
        array $excludedAccountsIds,
        DateTimeInterface $startedAt
    ): Budget {
        $accounts = [];
        foreach ($excludedAccountsIds as $excludedAccountId) {
            $account = $this->accountRepository->getReference($excludedAccountId);
            $accounts[] = $account;
        }
        return new Budget(
            $this->userRepository->getReference($userId),
            $id,
            $name,
            $accounts,
            $startedAt,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
