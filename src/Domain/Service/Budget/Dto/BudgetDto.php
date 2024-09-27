<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

class BudgetDto
{
    public function __construct(
        public BudgetMetaDto $meta,
        public BudgetFiltersDto $filters,
        public BudgetFinancialSummaryDto $financialSummary,
        public BudgetStructureDto $structure
    ) {
    }
}
