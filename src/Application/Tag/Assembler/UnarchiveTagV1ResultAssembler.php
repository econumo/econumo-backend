<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\UnarchiveTagV1RequestDto;
use App\Application\Tag\Dto\UnarchiveTagV1ResultDto;

class UnarchiveTagV1ResultAssembler
{
    public function assemble(
        UnarchiveTagV1RequestDto $dto
    ): UnarchiveTagV1ResultDto {
        return new UnarchiveTagV1ResultDto();
    }
}
