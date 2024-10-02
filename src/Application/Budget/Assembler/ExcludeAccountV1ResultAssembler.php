<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\ExcludeAccountV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class ExcludeAccountV1ResultAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaToResultDtoAssembler
    ) {
    }

    public function assemble(
        BudgetMetaDto $budgetDto
    ): ExcludeAccountV1ResultDto {
        $result = new ExcludeAccountV1ResultDto();
        $result->item = $this->budgetMetaToResultDtoAssembler->assemble($budgetDto);

        return $result;
    }
}
