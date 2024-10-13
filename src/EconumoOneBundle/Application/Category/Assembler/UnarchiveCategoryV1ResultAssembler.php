<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Dto\UnarchiveCategoryV1RequestDto;
use App\EconumoOneBundle\Application\Category\Dto\UnarchiveCategoryV1ResultDto;

class UnarchiveCategoryV1ResultAssembler
{
    public function assemble(
        UnarchiveCategoryV1RequestDto $dto
    ): UnarchiveCategoryV1ResultDto {
        return new UnarchiveCategoryV1ResultDto();
    }
}
