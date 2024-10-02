<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\IncludeAccountV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class IncludeAccountV1ResultAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetMetaDto $budgetDto
    ): IncludeAccountV1ResultDto {
        $result = new IncludeAccountV1ResultDto();
        $result->item = $this->budgetMetaToResultDtoAssembler->assemble($budgetDto);

        return $result;
    }
}
