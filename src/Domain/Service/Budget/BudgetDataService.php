<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;


use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Assembler\AverageCurrencyRateDtoAssembler;
use App\Domain\Service\Budget\Assembler\BudgetEntityBudgetAmountDtoAssembler;
use App\Domain\Service\Budget\Assembler\BudgetEntitySpendAmountDtoAssembler;
use App\Domain\Service\Budget\Assembler\CurrencyBalanceDtoAssembler;
use App\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\Domain\Service\Budget\Dto\BudgetDataDto;
use App\Domain\Service\Budget\Dto\BudgetEntityBudgetAmountDto;
use App\Domain\Service\Budget\Dto\BudgetEntitySpendAmountDto;
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
        private BudgetEntityBudgetAmountDtoAssembler $budgetEntityBudgetAmountDtoAssembler,
        private BudgetEntitySpendAmountDtoAssembler $budgetEntitySpendAmountDtoAssembler,
    ) {
    }

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto
    {
        $periodStart = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $periodEnd = $periodStart->modify('last day of this month')->setTime(23, 59, 59);
        $budgetStructure = $this->budgetStructureService->getBudgetStructure($userId, $budgetId);

        $currencyBalances = $this->getCurrencyBalances($periodStart, $periodEnd, $budgetStructure);
        $averageCurrencyRates = $this->getAverageCurrencyRates($periodStart, $periodEnd, $budgetStructure);
        $entityBudgetAmounts = $this->getEntityBudgetAmounts($periodStart, $budgetStructure);
        $entitySpendAmounts = $this->getEntitySpendAmounts($periodStart, $periodEnd, $budgetStructure);

        return new BudgetDataDto(
            $budgetId,
            $periodStart,
            $periodEnd,
            $currencyBalances,
            $averageCurrencyRates,
            $entityBudgetAmounts,
            $entitySpendAmounts
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
     * @param BudgetStructureDto $budgetStructureDto
     * @return BudgetEntityBudgetAmountDto[]
     */
    private function getEntityBudgetAmounts(
        DateTimeInterface $periodStart,
        BudgetStructureDto $budgetStructureDto
    ): array {
        return $this->budgetEntityBudgetAmountDtoAssembler->assemble($budgetStructureDto->id, $periodStart);
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetStructureDto $budgetStructureDto
     * @return BudgetEntitySpendAmountDto[]
     */
    private function getEntitySpendAmounts(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetStructureDto $budgetStructureDto
    ): array {
        return $this->budgetEntitySpendAmountDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructureDto);
    }
}
