<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Budget;


use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Budget\Assembler\AverageCurrencyRateDtoAssembler;
use App\EconumoBundle\Domain\Service\Budget\Assembler\BudgetElementsAmountDtoAssembler;
use App\EconumoBundle\Domain\Service\Budget\Assembler\CurrencyBalanceDtoAssembler;
use App\EconumoBundle\Domain\Service\Budget\BudgetStructureService;
use App\EconumoBundle\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetDataDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetFiltersDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\CurrencyBalanceDto;
use DateTimeImmutable;
use DateTimeInterface;

readonly class BudgetDataService
{
    public function __construct(
        private BudgetStructureService $budgetStructureService,
        private CurrencyBalanceDtoAssembler $currencyBalanceDtoAssembler,
        private AverageCurrencyRateDtoAssembler $averageCurrencyRateDtoAssembler,
        private BudgetElementsAmountDtoAssembler $budgetEntityAmountDtoAssembler,
    ) {
    }

    public function getData(Id $userId, Id $budgetId, DateTimeInterface $period): BudgetDataDto
    {
        $periodStart = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $periodEnd = $periodStart->modify('next month');
        $budgetStructure = $this->budgetStructureService->getBudgetStructure($userId, $budgetId);

        $currencyBalances = $this->getCurrencyBalances($periodStart, $periodEnd, $budgetStructure);
        $averageCurrencyRates = $this->getAverageCurrencyRates($periodStart, $periodEnd, $budgetStructure);
        $entityAmounts = $this->getEntityAmounts($periodStart, $periodEnd, $budgetStructure);

        return new BudgetDataDto(
            $budgetId,
            $periodStart,
            $periodStart->modify('last day of this month')->setTime(23, 59, 59),
            $currencyBalances,
            $averageCurrencyRates,
            $entityAmounts
        );
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetFiltersDto $budgetStructureDto
     * @return CurrencyBalanceDto[]
     */
    private function getCurrencyBalances(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetFiltersDto $budgetStructureDto
    ): array {
        return $this->currencyBalanceDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructureDto);
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetFiltersDto $budgetStructureDto
     * @return AverageCurrencyRateDto[]
     */
    private function getAverageCurrencyRates(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetFiltersDto $budgetStructureDto
    ): array {
        return $this->averageCurrencyRateDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructureDto);
    }

    /**
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BudgetFiltersDto $budgetStructure
     * @return BudgetEntityAmountDto[]
     */
    private function getEntityAmounts(
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd,
        BudgetFiltersDto $budgetStructure
    ): array {
        return $this->budgetEntityAmountDtoAssembler->assemble($periodStart, $periodEnd, $budgetStructure);
    }
}
