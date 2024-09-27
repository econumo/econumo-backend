<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetBudgetListV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class GetBudgetListV1ResultAssembler
{
    public function __construct(
        private BudgetPreviewDtoToResultDtoAssembler $budgetPreviewDtoAssembler
    ) {
    }

    /**
     * @param BudgetMetaDto[] $budgets
     * @return GetBudgetListV1ResultDto
     */
    public function assemble(
        array $budgets
    ): GetBudgetListV1ResultDto {
        $result = new GetBudgetListV1ResultDto();
        $result->items = [];
        foreach ($budgets as $budget) {
            $result->items[] = $this->budgetPreviewDtoAssembler->assemble($budget);
        }

        return $result;
    }
}
