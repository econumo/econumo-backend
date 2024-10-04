<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\BudgetStructureParentElementResultDto;
use App\Domain\Service\Budget\Dto\BudgetStructureParentElementDto;

readonly class BudgetParentElementToResultDtoAssembler
{
    public function __construct(
        private BudgetChildElementToResultDtoAssembler $budgetChildElementToResultDtoAssembler,
        private BudgetCurrencyAmountToResultDtoAssembler $budgetCurrencyAmountToResultDtoAssembler
    ) {
    }

    public function assemble(BudgetStructureParentElementDto $dto): BudgetStructureParentElementResultDto
    {
        $result = new BudgetStructureParentElementResultDto();
        $result->id = $dto->id->getValue();
        $result->type = $dto->type->getValue();
        $result->name = $dto->name->getValue();
        $result->icon = $dto->icon->getValue();
        $result->currencyId = $dto->currencyId?->getValue();
        $result->isArchived = $dto->isArchived ? 1 : 0;
        $result->folderId = $dto->folderId?->getValue();
        $result->position = $dto->position;
        $result->budget = $dto->budgeted;
        $result->available = $dto->available;
        $result->spent = $dto->spent;

//        $result->currenciesSpent = [];
//        foreach ($dto->currenciesSpent as $currencySpent) {
//            $result->currenciesSpent[] = $this->budgetCurrencyAmountToResultDtoAssembler->assemble($currencySpent);
//        }

        $result->children = [];
        foreach ($dto->children as $child) {
            $result->children[] = $this->budgetChildElementToResultDtoAssembler->assemble($child);
        }

        return $result;
    }
}
