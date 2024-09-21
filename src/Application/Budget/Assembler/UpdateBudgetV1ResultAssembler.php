<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\UpdateBudgetV1RequestDto;
use App\Application\Budget\Dto\UpdateBudgetV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class UpdateBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetPreviewDtoToResultDtoAssembler $budgetPreviewDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetDto $budgetDto
    ): UpdateBudgetV1ResultDto {
        $result = new UpdateBudgetV1ResultDto();
        $result->item = $this->budgetPreviewDtoToResultDtoAssembler->assemble($budgetDto);

        return $result;
    }
}
