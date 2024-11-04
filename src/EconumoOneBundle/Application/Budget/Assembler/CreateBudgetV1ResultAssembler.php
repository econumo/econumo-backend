<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Assembler\BudgetDtoToResultDtoAssembler;
use App\EconumoOneBundle\Application\Budget\Dto\CreateBudgetV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetDto;

readonly class CreateBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetDtoToResultDtoAssembler $budgetDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetDto $budgetDto
    ): CreateBudgetV1ResultDto {
        $result = new CreateBudgetV1ResultDto();
        $result->item = $this->budgetDtoToResultDtoAssembler->assemble($budgetDto);
        return $result;
    }
}
