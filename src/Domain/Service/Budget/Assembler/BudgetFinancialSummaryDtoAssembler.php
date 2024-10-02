<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;
use App\Domain\Service\Budget\Dto\BudgetFinancialSummaryDto;
use App\Domain\Service\Budget\Dto\CurrencyBalanceDto;
use DateTimeInterface;

readonly class BudgetFinancialSummaryDtoAssembler
{
    public function __construct(
        private CurrencyBalanceDtoAssembler $currencyBalanceDtoAssembler,
        private AverageCurrencyRateDtoAssembler $averageCurrencyRateDtoAssembler,
    ) {
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param Id[] $accountsIds
     * @param Id[] $currenciesIds
     * @return BudgetFinancialSummaryDto
     */
    public function assemble(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $currenciesIds,
        array $accountsIds,
    ): BudgetFinancialSummaryDto {
        $currencyBalances = $this->getCurrencyBalances(
            $periodStart,
            $periodEnd,
            $accountsIds,
            $currenciesIds
        );
        $averageCurrencyRates = $this->getAverageCurrencyRates(
            $periodStart,
            $periodEnd,
            $currenciesIds
        );

        return new BudgetFinancialSummaryDto(
            $currencyBalances,
            $averageCurrencyRates
        );
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param Id[] $includedAccountsIds
     * @param Id[] $currenciesIds
     * @return CurrencyBalanceDto[]
     */
    private function getCurrencyBalances(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $includedAccountsIds,
        array $currenciesIds
    ): array {
        return $this->currencyBalanceDtoAssembler->assemble(
            $periodStart,
            $periodEnd,
            $includedAccountsIds,
            $currenciesIds
        );
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param Id[] $currenciesIds
     * @return AverageCurrencyRateDto[]
     */
    private function getAverageCurrencyRates(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        array $currenciesIds
    ): array {
        return $this->averageCurrencyRateDtoAssembler->assemble($periodStart, $periodEnd, $currenciesIds);
    }
}