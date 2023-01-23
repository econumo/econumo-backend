<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\UpdateTagV1RequestDto;
use App\Application\Tag\Dto\UpdateTagV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

class UpdateTagV1ResultAssembler
{
    public function __construct(private readonly TagIdToDtoResultAssembler $tagIdToDtoResultAssembler)
    {
    }

    public function assemble(
        UpdateTagV1RequestDto $dto
    ): UpdateTagV1ResultDto {
        $result = new UpdateTagV1ResultDto();
        $result->item = $this->tagIdToDtoResultAssembler->assemble(new Id($dto->id));

        return $result;
    }
}
