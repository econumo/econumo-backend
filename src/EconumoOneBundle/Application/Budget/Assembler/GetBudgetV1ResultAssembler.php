<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Assembler\BudgetDtoToResultDtoAssembler;
use App\EconumoOneBundle\Application\Budget\Dto\GetBudgetV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetDto;

readonly class GetBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetDtoToResultDtoAssembler $budgetDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetDto $budget
    ): GetBudgetV1ResultDto {
        $result = new GetBudgetV1ResultDto();
        $result->item = $this->budgetDtoToResultDtoAssembler->assemble($budget);

        return $result;
    }
}
