<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Dto\DeleteCategoryV1RequestDto;
use App\EconumoOneBundle\Application\Category\Dto\DeleteCategoryV1ResultDto;

class DeleteCategoryV1ResultAssembler
{
    public function assemble(
        DeleteCategoryV1RequestDto $dto
    ): DeleteCategoryV1ResultDto {
        return new DeleteCategoryV1ResultDto();
    }
}
