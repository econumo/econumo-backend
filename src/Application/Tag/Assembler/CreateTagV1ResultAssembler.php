<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Assembler\TagToUserTagDtoResultAssembler;
use App\Application\Tag\Dto\CreateTagV1RequestDto;
use App\Application\Tag\Dto\CreateTagV1ResultDto;
use App\Domain\Entity\Tag;

class CreateTagV1ResultAssembler
{
    public function __construct(private readonly TagToUserTagDtoResultAssembler $tagToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        CreateTagV1RequestDto $dto,
        Tag $tag
    ): CreateTagV1ResultDto {
        $result = new CreateTagV1ResultDto();
        $result->item = $this->tagToDtoV1ResultAssembler->assemble($tag);

        return $result;
    }
}
