<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\DeleteTagV1RequestDto;
use App\Application\Tag\Dto\DeleteTagV1ResultDto;

class DeleteTagV1ResultAssembler
{
    public function assemble(
        DeleteTagV1RequestDto $dto
    ): DeleteTagV1ResultDto {
        return new DeleteTagV1ResultDto();
    }
}
