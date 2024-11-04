<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\GrantAccessV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\GrantAccessV1ResultDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetMetaDto;

readonly class GrantAccessV1ResultAssembler
{
    public function __construct(
        private BudgetMetaToResultDtoAssembler $budgetMetaToResultDtoAssembler
    ) {
    }

    /**
     * @param BudgetMetaDto[] $budgets
     * @return GrantAccessV1ResultDto
     */
    public function assemble(
        array $budgets
    ): GrantAccessV1ResultDto {
        $result = new GrantAccessV1ResultDto();
        $result->items = [];
        foreach ($budgets as $budget) {
            $result->items[] = $this->budgetMetaToResultDtoAssembler->assemble($budget);
        }

        return $result;
    }
}
