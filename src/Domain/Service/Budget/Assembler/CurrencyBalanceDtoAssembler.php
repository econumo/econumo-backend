<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget\Assembler;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\Domain\Service\Budget\Dto\CurrencyBalanceDto;
use App\Domain\Service\DatetimeServiceInterface;
use DateTimeInterface;

readonly class CurrencyBalanceDtoAssembler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private DatetimeServiceInterface $datetimeService,
    ) {
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructureDto
     * @return CurrencyBalanceDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructureDto
    ): array {
        $now = $this->datetimeService->getCurrentDatetime();
        $startBalances = [];
        if ($periodStart <= $now) {
            $startBalances = $this->accountRepository->getAccountsBalancesOnDate($budgetStructureDto->includedAccountsIds, $periodStart);
        }
        $endBalances = [];
        if ($periodEnd <= $now){
            $endBalances = $this->accountRepository->getAccountsBalancesBeforeDate($budgetStructureDto->includedAccountsIds, $periodEnd);
        }
        $reports = [];
        if ($periodStart <= $now) {
            $reports = $this->accountRepository->getAccountsReport($budgetStructureDto->includedAccountsIds, $periodStart, $periodEnd);
        }

        $result = [];
        foreach ($budgetStructureDto->currencies as $currencyId) {
            $startBalance = $this->summarize($startBalances, $currencyId, 'balance');
            $endBalance = $this->summarize($endBalances, $currencyId, 'balance');
            $income = $this->summarize($reports, $currencyId, 'incomes');
            $expenses = $this->summarize($reports, $currencyId, 'expenses');
            $exchanges = $this->summarize($reports, $currencyId, 'exchange_incomes') - $this->summarize(
                    $reports,
                    $currencyId,
                    'exchange_expenses'
                );
            $holdings = null;
            $item = new CurrencyBalanceDto(
                $currencyId,
                ($periodStart <= $now ? $startBalance : null),
                ($periodEnd <= $now ? $endBalance : null),
                ($periodStart <= $now ? $income : null),
                ($periodStart <= $now ? $expenses : null),
                ($periodStart <= $now ? round($exchanges, 2) : null),
                $holdings
            );
            $result[] = $item;
        }

        return $result;
    }

    private function summarize(array $items, Id $currencyId, string $field): float
    {
        $result = .0;
        foreach ($items as $item) {
            if ($item['currency_id'] === $currencyId->getValue()) {
                $result += $item[$field];
            }
        }

        return round($result, 2);
    }
}