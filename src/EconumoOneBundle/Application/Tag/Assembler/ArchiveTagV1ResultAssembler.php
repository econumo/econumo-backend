<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Assembler;

use App\EconumoOneBundle\Application\Tag\Dto\ArchiveTagV1RequestDto;
use App\EconumoOneBundle\Application\Tag\Dto\ArchiveTagV1ResultDto;

class ArchiveTagV1ResultAssembler
{
    public function assemble(
        ArchiveTagV1RequestDto $dto
    ): ArchiveTagV1ResultDto {
        return new ArchiveTagV1ResultDto();
    }
}
