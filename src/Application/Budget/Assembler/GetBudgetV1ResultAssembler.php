<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetBudgetV1ResultDto;
use App\Domain\Service\Budget\Dto\BudgetDto;

readonly class GetBudgetV1ResultAssembler
{
    public function assemble(
        BudgetDto $budgetDto
    ): GetBudgetV1ResultDto {
        $result = new GetBudgetV1ResultDto();
        $result->result = 'test';

        return $result;
    }
}
