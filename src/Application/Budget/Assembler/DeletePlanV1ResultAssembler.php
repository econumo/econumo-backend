<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\DeletePlanV1RequestDto;
use App\Application\Budget\Dto\DeletePlanV1ResultDto;

class DeletePlanV1ResultAssembler
{
    public function assemble(
        DeletePlanV1RequestDto $dto
    ): DeletePlanV1ResultDto {
        return new DeletePlanV1ResultDto();
    }
}
