<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Assembler\AverageCurrencyRateDtoAssembler;
use App\Domain\Service\Budget\Assembler\BudgetEntityAmountDtoAssembler;
use App\Domain\Service\Budget\Assembler\CurrencyBalanceDtoAssembler;
use App\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\Domain\Service\Budget\Dto\BudgetDataDto;
use App\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\Domain\Service\Budget\Dto\BudgetStructureDto;
use App\Domain\Service\Budget\Dto\CurrencyBalanceDto;
use DateTimeImmutable;
use DateTimeInterface;

readonly class BudgetDataService
{
    public function __construct(
        private BudgetStructureService $budgetStructureService,
        private CurrencyBalanceDtoAssembler $currencyBalanceDtoAssembler,
        private AverageCurrencyRateDtoAssembler $averageCurrencyRateDtoAssembler,
        private BudgetEntityAmountDtoAssembler $budgetEntityAmountDtoAssembler,
    ) {
    }

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto
    {
        $periodStart = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $periodEnd = $periodStart->modify('last day of this month')->setTime(23, 59, 59);
        $budgetStructure = $this->budgetStructureService->getBudgetStructure($userId, $budgetId);

        $currencyBalances = $this->getCurrencyBalances($periodStart, $periodEnd, $budgetStructure);
        $averageCurrencyRates = $this->getAverageCurrencyRates($periodStart, $periodEnd, $budgetStructure);
        $entityAmounts = $this->getEntityAmounts($periodStart, $periodEnd, $budgetStructure);

        return new BudgetDataDto(
            $budgetId,
            $periodStart,
            $periodEnd,
            $currencyBalances,
            $averageCurrencyRates,
            $entityAmounts
        );
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructureDto
     * @return CurrencyBalanceDto[]
     */
    private function getCurrencyBalances(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructureDto
    ): array {
        return $this->currencyBalanceDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructureDto);
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructureDto
     * @return AverageCurrencyRateDto[]
     */
    private function getAverageCurrencyRates(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructureDto
    ): array {
        return $this->averageCurrencyRateDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructureDto);
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructure
     * @return BudgetEntityAmountDto[]
     */
    private function getEntityAmounts(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructure
    ): array {
        return $this->budgetEntityAmountDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructure);
    }
}