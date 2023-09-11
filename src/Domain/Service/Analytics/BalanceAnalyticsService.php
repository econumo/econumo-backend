<?php

declare(strict_types=1);


namespace App\Domain\Service\Analytics;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\Currency\CurrencyConvertorInterface;
use App\Domain\Service\Dto\BalanceAnalyticsDto;
use DateInterval;
use DatePeriod;
use DateTimeInterface;

readonly class BalanceAnalyticsService implements BalanceAnalyticsServiceInterface
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly CurrencyConvertorInterface $currencyConvertor,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getBalanceAnalytics(DateTimeInterface $from, DateTimeInterface $to, Id $userId): array
    {
        $result = [];
        $accounts = $this->accountRepository->getUserAccounts($userId);
        $user = $this->userRepository->get($userId);

        foreach (new DatePeriod($from, new DateInterval('P1M'), $to) as $date) {
            $balance = 0.0;
            foreach ($accounts as $account) {
                if ($account->isDeleted()) {
                    continue;
                }
                if ($account->getUserId()->isEqual($userId) === false) {
                    continue;
                }
                $accountBalance = $this->transactionRepository->getBalance($account->getId(), $date);
                $accountBalanceConverted = $this->currencyConvertor->convert(
                    $account->getCurrencyCode(),
                    $user->getCurrency(),
                    $accountBalance
                );
                $balance += $accountBalanceConverted;
            }
            $item = new BalanceAnalyticsDto();
            $item->balance = $balance;
            $item->date = $date;
            $result[] = $item;
        }

        return $result;
    }
}
