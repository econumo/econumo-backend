<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetListItemResultDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class BudgetPreviewDtoToResultDtoAssembler
{
    public function __construct(
        private BudgetAccessToResultDtoAssembler $budgetAccessToResultDtoAssembler
    ) {
    }

    public function assemble(BudgetMetaDto $budgetMeta): BudgetListItemResultDto
    {
        $item = new BudgetListItemResultDto();
        $item->id = $budgetMeta->id->getValue();
        $item->ownerUserId = $budgetMeta->ownerUserId->getValue();
        $item->name = $budgetMeta->budgetName->getValue();
        $item->startedAt = $budgetMeta->startedAt->format('Y-m-d H:i:s');
        $item->sharedAccess = [];
        foreach ($budgetMeta->sharedAccess as $access) {
            $item->sharedAccess[] = $this->budgetAccessToResultDtoAssembler->assemble($access);
        }

        return $item;
    }
}
