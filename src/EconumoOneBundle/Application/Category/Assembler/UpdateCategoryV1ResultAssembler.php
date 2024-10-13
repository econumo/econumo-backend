<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Dto\UpdateCategoryV1RequestDto;
use App\EconumoOneBundle\Application\Category\Dto\UpdateCategoryV1ResultDto;

class UpdateCategoryV1ResultAssembler
{
    public function assemble(
        UpdateCategoryV1RequestDto $dto
    ): UpdateCategoryV1ResultDto {
        return new UpdateCategoryV1ResultDto();
    }
}
