<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\CreateBudgetV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class CreateBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaDtoToResultDtoAssembler,
    ) {
    }

    public function assemble(
        BudgetMetaDto $budgetDto
    ): CreateBudgetV1ResultDto {
        $result = new CreateBudgetV1ResultDto();
        $result->item = $this->budgetMetaDtoToResultDtoAssembler->assemble($budgetDto);
        return $result;
    }
}
