<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetFiltersDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetFinancialSummaryDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetMetaDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetStructureDto;

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
