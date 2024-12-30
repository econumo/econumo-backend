<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Builder;

use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetDto;
use DateTimeImmutable;
use DateTimeInterface;

readonly class BudgetBuilder
{
    public function __construct(
        private BudgetMetaBuilder $budgetMetaDtoAssembler,
        private BudgetFiltersBuilder $budgetFiltersDtoAssembler,
        private BudgetFinancialSummaryBuilder $budgetFinancialSummaryDtoAssembler,
        private BudgetElementsSpendingBuilder $budgetElementsSpendingDtoAssembler,
        private BudgetStructureBuilder $budgetStructureDtoAssembler,
        private BudgetElementsLimitsBuilder $budgetElementsLimitsDtoAssembler
    ) {
    }

    public function build(
        Id $userId,
        Budget $budget,
        DateTimeInterface $periodStart
    ): BudgetDto {
        $periodStart = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $periodStart->format('Y-m-01 00:00:00'));
        $periodEnd = $periodStart->modify('next month');
        $budgetMeta = $this->budgetMetaDtoAssembler->build($budget);
        $budgetFilters = $this->budgetFiltersDtoAssembler->build($budget, $userId, $periodStart, $periodEnd);
        $budgetFinancialSummary = $this->budgetFinancialSummaryDtoAssembler->build(
            $budget->getCurrencyId(),
            $budgetFilters->periodStart,
            $budgetFilters->periodEnd,
            $budgetFilters->currenciesIds,
            $budgetFilters->includedAccountsIds
        );
        $elementsLimits = $this->budgetElementsLimitsDtoAssembler->build($budget, $budgetFilters);
        $elementsSpending = $this->budgetElementsSpendingDtoAssembler->build($budget, $budgetFilters);
        $budgetStructure = $this->budgetStructureDtoAssembler->build($budget, $budgetFilters, $elementsLimits, $elementsSpending);

        return new BudgetDto(
            $budgetMeta,
            $budgetFilters,
            $budgetFinancialSummary,
            $budgetStructure
        );
    }
}
