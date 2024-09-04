<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\CreateBudgetV1ResultDto;
use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;

readonly class CreateBudgetV1ResultAssembler
{
    public function __construct(
        private BudgetToResultDtoAssembler $budgetToResultDtoAssembler
    ) {
    }

    public function assemble(
        Id $userId,
        Budget $budget
    ): CreateBudgetV1ResultDto {
        $result = new CreateBudgetV1ResultDto();
        $result->item = $this->budgetToResultDtoAssembler->assemble($userId, $budget);
        return $result;
    }
}
