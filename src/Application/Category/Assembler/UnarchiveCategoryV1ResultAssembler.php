<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\UnarchiveCategoryV1RequestDto;
use App\Application\Category\Dto\UnarchiveCategoryV1ResultDto;

class UnarchiveCategoryV1ResultAssembler
{
    public function assemble(
        UnarchiveCategoryV1RequestDto $dto
    ): UnarchiveCategoryV1ResultDto {
        return new UnarchiveCategoryV1ResultDto();
    }
}
