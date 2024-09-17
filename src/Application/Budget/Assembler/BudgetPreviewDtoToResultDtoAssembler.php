<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetListItemResultDto;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;

readonly class BudgetPreviewDtoToResultDtoAssembler
{
    public function __construct(
        private BudgetAccessToResultDtoAssembler $budgetAccessToResultDtoAssembler
    ) {
    }

    public function assemble(BudgetPreviewDto $budgetPreviewDto): BudgetListItemResultDto
    {
        $item = new BudgetListItemResultDto();
        $item->id = $budgetPreviewDto->id->getValue();
        $item->ownerUserId = $budgetPreviewDto->ownerUserId->getValue();
        $item->name = $budgetPreviewDto->budgetName->getValue();
        $item->startedAt = $budgetPreviewDto->startedAt->format('Y-m-d H:i:s');
        $item->sharedAccess = [];
        foreach ($budgetPreviewDto->sharedAccess as $access) {
            $item->sharedAccess[] = $this->budgetAccessToResultDtoAssembler->assemble($access);
        }

        return $item;
    }
}
