<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\Dto\PlanDataBalanceDto;
use DateTimeInterface;

readonly class PlanBalanceService
{
    public function __construct(
        private PlanAccountsService $planAccountsService,
        private AccountRepositoryInterface $accountRepository,
        private DatetimeServiceInterface $datetimeService,
        private TransactionRepositoryInterface $transactionRepository
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
     * @param DateTimeInterface $currentPeriod
     * @return PlanDataBalanceDto[]
     */
    public function getBalance(Id $planId, DateTimeInterface $periodStart, DateTimeInterface $periodEnd, DateTimeInterface $currentPeriod): array
    {
        $currencies = [];
        $accountIds = [];
        foreach ($this->planAccountsService->getAvailableAccountsForPlanId($planId) as $account) {
            $accountIds[$account->getId()->getValue()] = $account->getId();
            $currencies[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
        }
        $accountIds = array_values($accountIds);
        $currencies = array_values($currencies);

        if ($periodStart < $currentPeriod) {
            $startBalanceData = $this->getBalanceData($accountIds, $periodStart);
        } else {
            $startBalanceData = [];
            foreach ($currencies as $currencyId) {
                $startBalanceData[$currencyId->getValue()] = [
                    'balance' => null,
                    'income' => null,
                    'expenses' => null,
                    'exchanges' => null
                ];
            }
        }

        $isCurrentPeriod = ($currentPeriod >= $periodStart && $currentPeriod < $periodEnd);
        if ($periodEnd < $currentPeriod || $isCurrentPeriod) {
            $endBalanceData = $this->getBalanceData($accountIds, $periodEnd);
            $endBalanceData = array_replace_recursive($endBalanceData, $this->getAccountsReports($accountIds, $periodStart, $periodEnd));
            if ($isCurrentPeriod) {
                foreach ($endBalanceData as $currencyId => $value) {
                    $endBalanceData[$currencyId]['balance'] = null;
                }
            }
        } else {
            $endBalanceData = [];
            foreach ($currencies as $currencyId) {
                $endBalanceData[$currencyId->getValue()] = [
                    'balance' => null,
                    'income' => null,
                    'expenses' => null,
                    'exchanges' => null
                ];
            }
        }
        $currentBalanceData = !$isCurrentPeriod ? [] : $this->getBalanceData($accountIds, $currentPeriod);

        $hoardAccountIds = [];
        foreach ($this->planAccountsService->getHoardAccountsForPlanId($planId) as $account) {
            $hoardAccountIds[$account->getId()->getValue()] = $account->getId();
        }
        $hoardReport = $this->getHoardsReport($currencies, $accountIds, array_values($hoardAccountIds), $periodStart, $periodEnd);

        $result = [];
        foreach ($currencies as $currencyId) {
            foreach ($startBalanceData as $startBalanceCurrencyId => $value) {
                if ($currencyId->getValue() !== $startBalanceCurrencyId) {
                    continue;
                }

                $dto = new PlanDataBalanceDto();
                $dto->currencyId = new Id($startBalanceCurrencyId);
                $dto->startBalance = $value['balance'] === null ? null : (float)$value['balance'];
                $dto->endBalance = $endBalanceData[$startBalanceCurrencyId]['balance'] === null ? null : (float)$endBalanceData[$startBalanceCurrencyId]['balance'];
                $dto->currentBalance = $currentBalanceData === [] ? null : ($currentBalanceData[$startBalanceCurrencyId]['balance'] === null ? null : (float)$currentBalanceData[$startBalanceCurrencyId]['balance']);

                $dto->income = $endBalanceData[$startBalanceCurrencyId]['income'] === null ? null : (float)$endBalanceData[$startBalanceCurrencyId]['income'];
                $dto->expenses = $endBalanceData[$startBalanceCurrencyId]['expenses'] === null ? null : (float)$endBalanceData[$startBalanceCurrencyId]['expenses'];
                $dto->exchanges = $endBalanceData[$startBalanceCurrencyId]['exchanges'] === null ? null : (float)$endBalanceData[$startBalanceCurrencyId]['exchanges'];
                $dto->hoards = !isset($hoardReport[$startBalanceCurrencyId]) ? null : floatval($hoardReport[$startBalanceCurrencyId]['from_hoards'] - $hoardReport[$startBalanceCurrencyId]['to_hoards']);
                $result[] = $dto;
            }
        }

        return $result;
    }

    /**
     * @param array $accountIds
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    private function getBalanceData(array $accountIds, DateTimeInterface $periodEnd): array
    {
        $balances = $this->accountRepository->getAccountsBalancesBeforeDate($accountIds, $periodEnd);
        $balanceData = [];
        foreach ($balances as $item) {
            if (!isset($balanceData[$item['currency_id']])) {
                $balanceData[$item['currency_id']] = [
                    'income' => null,
                    'expenses' => null,
                    'exchanges' => null,
                    'balance' => 0.0
                ];
            }
            $balanceData[$item['currency_id']]['balance'] += (float)$item['balance'];
        }
        arsort($balanceData, SORT_NUMERIC);

        return $balanceData;
    }

    /**
     * @param array $currenciesIds
     * @param array $reportAccountIds
     * @param array $hoardAccountIds
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    private function getHoardsReport(array $currenciesIds, array $reportAccountIds, array $hoardAccountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array
    {
        $result = [];
        foreach ($currenciesIds as $currencyId) {
            $result[$currencyId->getValue()] = [
                'to_hoards' => 0.0,
                'from_hoards' => 0.0,
            ];
        }
        $reports = $this->transactionRepository->getHoardsReport($reportAccountIds, $hoardAccountIds, $periodStart, $periodEnd);
        foreach ($reports as $currencyId => $item) {
            if (!isset($result[$currencyId])) {
                $result[$currencyId] = [
                    'to_hoards' => 0.0,
                    'from_hoards' => 0.0,
                ];
            }
            $result[$currencyId]['to_hoards'] += (float)$item['to_hoards'];
            $result[$currencyId]['from_hoards'] += (float)$item['from_hoards'];
        }

        return $result;
    }

    /**
     * @param array $accountIds
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @return array
     */
    private function getAccountsReports(array $accountIds, DateTimeInterface $periodStart, DateTimeInterface $periodEnd): array
    {
        $reports = $this->transactionRepository->getAccountsReport($accountIds, $periodStart, $periodEnd);
        $result = [];
        foreach ($reports as $item) {
            if (!isset($result[$item['currency_id']])) {
                $result[$item['currency_id']] = [
                    'income' => 0.0,
                    'expenses' => 0.0,
                    'transfers' => 0.0,
                    'exchanges' => 0.0,
                ];
            }
            $result[$item['currency_id']]['income'] += (float)$item['incomes'];
            $result[$item['currency_id']]['expenses'] += (float)$item['expenses'];
            $result[$item['currency_id']]['transfers'] += (float)$item['transfer_incomes'];
            $result[$item['currency_id']]['transfers'] -= (float)$item['transfer_expenses'];
            $result[$item['currency_id']]['exchanges'] += (float)$item['exchange_incomes'];
            $result[$item['currency_id']]['exchanges'] -= (float)$item['exchange_expenses'];
        }

        return $result;
    }
}
