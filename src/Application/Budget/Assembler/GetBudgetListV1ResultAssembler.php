<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\BudgetListItemResultDto;
use App\Application\Budget\Dto\GetBudgetListV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;

readonly class GetBudgetListV1ResultAssembler
{
    public function __construct(
        private BudgetAccessToResultDtoAssembler $budgetAccessToResultDtoAssembler
    ) {
    }

    /**
     * @param BudgetPreviewDto[] $budgets
     * @return GetBudgetListV1ResultDto
     */
    public function assemble(
        array $budgets
    ): GetBudgetListV1ResultDto {
        $result = new GetBudgetListV1ResultDto();
        $result->items = [];
        foreach ($budgets as $budget) {
            $item = new BudgetListItemResultDto();
            $item->id = $budget->id->getValue();
            $item->ownerUserId = $budget->ownerUserId->getValue();
            $item->name = $budget->budgetName->getValue();
            $item->startedAt = $budget->startedAt->format('Y-m-d H:i:s');
            $item->sharedAccess = [];
            foreach ($budget->sharedAccess as $access) {
                $item->sharedAccess[] = $this->budgetAccessToResultDtoAssembler->assemble($access);
            }

            $result->items[] = $item;
        }

        return $result;
    }
}
