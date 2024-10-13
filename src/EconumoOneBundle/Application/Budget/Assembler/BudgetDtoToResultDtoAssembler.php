<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Application\Budget\Assembler;


use App\EconumoOneBundle\Application\Budget\Assembler\BudgetCurrencyBalanceToResultDtoAssembler;
use App\EconumoOneBundle\Application\Budget\Assembler\BudgetFiltersToResultDtoAssembler;
use App\EconumoOneBundle\Application\Budget\Assembler\BudgetStructureToResultDtoAssembler;
use App\EconumoOneBundle\Application\Budget\Dto\BudgetResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\BudgetMetaToResultDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetDto;

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
