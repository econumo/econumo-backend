<?php

declare(strict_types=1);


namespace App\EconumoBundle\Application\Budget\Assembler;

use App\EconumoBundle\Application\Budget\Assembler\BudgetCurrencyAmountToResultDtoAssembler;
use App\EconumoBundle\Application\Budget\Dto\BudgetStructureChildElementResultDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetStructureChildElementDto;

readonly class BudgetChildElementToResultDtoAssembler
{
    public function __construct(
        private BudgetCurrencyAmountToResultDtoAssembler $budgetCurrencyAmountToResultDtoAssembler
    ) {
    }

    public function assemble(BudgetStructureChildElementDto $dto): BudgetStructureChildElementResultDto
    {
        $result = new BudgetStructureChildElementResultDto();
        $result->id = $dto->id->getValue();
        $result->type = $dto->type->getValue();
        $result->name = $dto->name->getValue();
        $result->icon = $dto->icon->getValue();
        $result->isArchived = $dto->isArchived ? 1 : 0;
        $result->spent = $dto->spent;
//        $result->currenciesSpent = [];
//        foreach ($dto->currenciesSpent as $currencySpent) {
//            $result->currenciesSpent[] = $this->budgetCurrencyAmountToResultDtoAssembler->assemble($currencySpent);
//        }

        return $result;
    }
}
