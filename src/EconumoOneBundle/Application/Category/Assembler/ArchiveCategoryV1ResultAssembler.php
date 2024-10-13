<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Dto\ArchiveCategoryV1RequestDto;
use App\EconumoOneBundle\Application\Category\Dto\ArchiveCategoryV1ResultDto;

class ArchiveCategoryV1ResultAssembler
{
    public function assemble(
        ArchiveCategoryV1RequestDto $dto
    ): ArchiveCategoryV1ResultDto {
        return new ArchiveCategoryV1ResultDto();
    }
}
