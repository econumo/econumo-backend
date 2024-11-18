<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget\Assembler;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\AccountRepositoryInterface;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetFiltersDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\CurrencyBalanceDto;
use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
use DateTimeInterface;

readonly class CurrencyBalanceDtoAssembler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private DatetimeServiceInterface $datetimeService,
    ) {
    }

    /**
     * @param Id[] $includedAccountsIds
     * @param Id[] $currenciesIds
     * @return CurrencyBalanceDto[]
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $includedAccountsIds,
        array $currenciesIds
    ): array {
        $now = $this->datetimeService->getCurrentDatetime();
        $startBalances = [];
        if ($periodStart <= $now) {
            $startBalances = $this->accountRepository->getAccountsBalancesOnDate($includedAccountsIds, $periodStart);
        }

        $endBalances = [];
        if ($periodEnd <= $now){
            $endBalances = $this->accountRepository->getAccountsBalancesBeforeDate($includedAccountsIds, $periodEnd);
        }

        $reports = [];
        if ($periodStart <= $now) {
            $reports = $this->accountRepository->getAccountsReport($includedAccountsIds, $periodStart, $periodEnd);
        }

        $result = [];
        foreach ($currenciesIds as $currencyId) {
            $startBalance = $this->summarize($startBalances, $currencyId, 'balance');
            $endBalance = $this->summarize($endBalances, $currencyId, 'balance');
            $income = $this->summarize($reports, $currencyId, 'incomes');
            $expenses = $this->summarize($reports, $currencyId, 'expenses');
            $exchanges = $this->summarize($reports, $currencyId, 'exchange_incomes') - $this->summarize(
                    $reports,
                    $currencyId,
                    'exchange_expenses'
                );
            // @todo fix holdings
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
