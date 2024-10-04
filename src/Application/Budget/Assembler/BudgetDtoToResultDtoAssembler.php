<?php

declare(strict_types=1);


namespace App\Application\Budget\Assembler;


use App\Application\Budget\Dto\BudgetResultDto;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class BudgetDtoToResultDtoAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaToResultDtoAssembler,
        private BudgetStructureToResultDtoAssembler $budgetStructureToResultDtoAssembler,
        private BudgetCurrencyBalanceToResultDtoAssembler $budgetCurrencyBalanceToResultDtoAssembler,
        private BudgetFiltersToResultDtoAssembler $budgetFiltersToResultDtoAssembler,
    ) {
    }

    public function assemble(BudgetDto $dto): BudgetResultDto
    {
        $result = new BudgetResultDto();
        $result->meta = $this->budgetMetaToResultDtoAssembler->assemble($dto->meta);
        $result->filters = $this->budgetFiltersToResultDtoAssembler->assemble($dto->filters);
        $result->balances = [];
        foreach ($dto->financialSummary->currencyBalances as $balance) {
            $result->balances[] = $this->budgetCurrencyBalanceToResultDtoAssembler->assemble($balance);
        }
        $result->structure = $this->budgetStructureToResultDtoAssembler->assemble($dto->structure);

        return $result;
    }
}
