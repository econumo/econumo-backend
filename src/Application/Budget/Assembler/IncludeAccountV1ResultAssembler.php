<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\IncludeAccountV1RequestDto;
use App\Application\Budget\Dto\IncludeAccountV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetPreviewDto;

readonly class IncludeAccountV1ResultAssembler
{
    public function __construct(
        private BudgetPreviewDtoToResultDtoAssembler $budgetPreviewDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetPreviewDto $budgetDto
    ): IncludeAccountV1ResultDto {
        $result = new IncludeAccountV1ResultDto();
        $result->item = $this->budgetPreviewDtoToResultDtoAssembler->assemble($budgetDto);

        return $result;
    }
}
