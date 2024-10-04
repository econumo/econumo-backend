<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Assembler;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDto;
use DateTimeImmutable;
use DateTimeInterface;

readonly class BudgetDtoAssembler
{
    public function __construct(
        private BudgetMetaDtoAssembler $budgetMetaDtoAssembler,
        private BudgetFiltersDtoAssembler $budgetFiltersDtoAssembler,
        private BudgetFinancialSummaryDtoAssembler $budgetFinancialSummaryDtoAssembler,
        private BudgetElementsAmountDtoAssembler $budgetElementsAmountDtoAssembler,
        private BudgetStructureDtoAssembler  $budgetStructureDtoAssembler,
    ) {
    }

    public function assemble(
        Id $userId,
        Budget $budget,
        DateTimeInterface $periodStart
    ): BudgetDto {
        $periodStart = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $periodStart->format('Y-m-01 00:00:00'));
        $periodEnd = $periodStart->modify('next month');
        $budgetMeta = $this->budgetMetaDtoAssembler->assemble($budget);
        $budgetFilters = $this->budgetFiltersDtoAssembler->assemble($budget, $userId, $periodStart, $periodEnd);
        $budgetFinancialSummary = $this->budgetFinancialSummaryDtoAssembler->assemble(
            $budgetFilters->periodStart,
            $budgetFilters->periodEnd,
            $budgetFilters->currenciesIds,
            $budgetFilters->includedAccountsIds
        );
        $elementsAmounts = $this->budgetElementsAmountDtoAssembler->assemble($budget, $budgetFilters);
        $budgetStructure = $this->budgetStructureDtoAssembler->assemble($budget, $elementsAmounts, $budgetFilters);

        return new BudgetDto(
            $budgetMeta,
            $budgetFilters,
            $budgetFinancialSummary,
            $budgetStructure
        );
    }
}
