<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\ResetBudgetV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class ResetBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetPreviewDtoToResultDtoAssembler $budgetPreviewDtoToResultDtoAssembler
    ) {
    }
    public function assemble(
        BudgetDto $budgetDto
    ): ResetBudgetV1ResultDto {
        $result = new ResetBudgetV1ResultDto();
        $result->item = $this->budgetPreviewDtoToResultDtoAssembler->assemble($budgetDto);

        return $result;
    }
}
