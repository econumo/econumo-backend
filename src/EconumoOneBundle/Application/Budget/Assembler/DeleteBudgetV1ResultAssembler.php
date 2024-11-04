<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Assembler;

use App\EconumoOneBundle\Application\Budget\Dto\DeleteBudgetV1RequestDto;
use App\EconumoOneBundle\Application\Budget\Dto\DeleteBudgetV1ResultDto;

readonly class DeleteBudgetV1ResultAssembler
{
    public function assemble(
    ): DeleteBudgetV1ResultDto {
        return new DeleteBudgetV1ResultDto();
    }
}
