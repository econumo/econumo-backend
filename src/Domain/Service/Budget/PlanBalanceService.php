<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\Dto\PlanDataBalanceDto;
use DateTimeInterface;

readonly class PlanBalanceService
{
    public function __construct(
        private PlanAccountsService $planAccountsService,
        private AccountRepositoryInterface $accountRepository,
        private DatetimeServiceInterface $datetimeService,
    ) {
    }

    /**
     * @param id[] $currenciesIds
     * @return PlanDataBalanceDto[]
     */
    public function getBalanceStubs(array $currenciesIds): array
    {
        $result = [];
        foreach ($currenciesIds as $currencyId) {
            $dto = new PlanDataBalanceDto();
            $dto->currencyId = $currencyId;
            $dto->startBalance = null;
            $dto->endBalance = null;
            $result[] = $dto;
        }

        return $result;
    }

    /**
     * @param Id $planId
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return PlanDataBalanceDto[]
     */
    public function getBalance(Id $planId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array
    {
        $currencies = [];
        $accountIds = [];
        foreach ($this->planAccountsService->getAvailableAccountsForPlanId($planId) as $account) {
            $accountIds[$account->getId()->getValue()] = $account->getId();
            $currencies[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
        }
        $accountIds = array_values($accountIds);
        $currencies = array_values($currencies);

        $currentDateTime = $this->datetimeService->getCurrentDatetime();
        if ($periodStart < $currentDateTime) {
            $startBalanceData = $this->getBalanceData($accountIds, $periodStart);
        } else {
            $startBalanceData = [];
            foreach ($currencies as $currencyId) {
                $startBalanceData[$currencyId->getValue()] = null;
            }
        }

        if ($periodEnd < $currentDateTime) {
            $endBalanceData = $this->getBalanceData($accountIds, $periodEnd);
        } else {
            $endBalanceData = [];
            foreach ($currencies as $currencyId) {
                $endBalanceData[$currencyId->getValue()] = null;
            }
        }

        $result = [];
        foreach ($currencies as $currencyId) {
            foreach ($startBalanceData as $startBalanceCurrencyId => $value) {
                if ($currencyId->getValue() !== $startBalanceCurrencyId) {
                    continue;
                }

                $dto = new PlanDataBalanceDto();
                $dto->currencyId = new Id($startBalanceCurrencyId);
                $dto->startBalance = $value === null ? null : (float)$value;
                $dto->endBalance = $endBalanceData[$startBalanceCurrencyId] === null ? null : (float)$endBalanceData[$startBalanceCurrencyId];
                $result[] = $dto;
            }
        }

        return $result;
    }

    /**
     * @param array $accountIds
     * @param DateTimeInterface $date
     * @return array
     */
    private function getBalanceData(array $accountIds, DateTimeInterface $date): array
    {
        $data = $this->accountRepository->getAccountsBalancesOnDate($accountIds, $date);
        $balanceData = [];
        foreach ($data as $item) {
            if (!isset($balanceData[$item['currency_id']])) {
                $balanceData[$item['currency_id']] = 0;
            }
            $balanceData[$item['currency_id']] += (float)$item['balance'];
        }

        return $balanceData;
    }
}
