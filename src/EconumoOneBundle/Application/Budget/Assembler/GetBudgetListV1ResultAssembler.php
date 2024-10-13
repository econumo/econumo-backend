<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\GetBudgetListV1ResultDto;
use App\EconumoOneBundle\Application\Budget\Assembler\BudgetMetaToResultDtoAssembler;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class GetBudgetListV1ResultAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaToResultDtoAssembler
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
            $result->items[] = $this->budgetMetaToResultDtoAssembler->assemble($budget);
        }

        return $result;
    }
}
