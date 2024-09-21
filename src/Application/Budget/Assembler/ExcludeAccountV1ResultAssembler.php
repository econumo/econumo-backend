<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\ExcludeAccountV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class ExcludeAccountV1ResultAssembler
{
    public function __construct(
        private BudgetPreviewDtoToResultDtoAssembler $budgetPreviewDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetDto $budgetDto
    ): ExcludeAccountV1ResultDto {
        $result = new ExcludeAccountV1ResultDto();
        $result->item = $this->budgetPreviewDtoToResultDtoAssembler->assemble($budgetDto);

        return $result;
    }
}
