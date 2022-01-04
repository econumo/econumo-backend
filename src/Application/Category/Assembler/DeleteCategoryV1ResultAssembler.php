<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\DeleteCategoryV1RequestDto;
use App\Application\Category\Dto\DeleteCategoryV1ResultDto;

class DeleteCategoryV1ResultAssembler
{
    public function assemble(
        DeleteCategoryV1RequestDto $dto
    ): DeleteCategoryV1ResultDto {
        return new DeleteCategoryV1ResultDto();
    }
}
