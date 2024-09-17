<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\DeleteBudgetV1RequestDto;
use App\Application\Budget\Dto\DeleteBudgetV1ResultDto;

readonly class DeleteBudgetV1ResultAssembler
{
    public function assemble(
    ): DeleteBudgetV1ResultDto {
        return new DeleteBudgetV1ResultDto();
    }
}
