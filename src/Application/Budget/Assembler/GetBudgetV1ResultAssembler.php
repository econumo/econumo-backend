<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetBudgetV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class GetBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetDtoToResultDtoAssembler $budgetDtoToResultDtoAssembler
    ) {
    }

    public function assemble(
        Id $userId,
        BudgetDto $budgetDto
    ): GetBudgetV1ResultDto {
        $result = new GetBudgetV1ResultDto();
        $result->item = $this->budgetDtoToResultDtoAssembler->assemble($userId, $budgetDto);

        return $result;
    }
}
