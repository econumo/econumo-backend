<?php

declare(strict_types=1);

namespace App\Application\Tag\Tag\Assembler;

use App\Application\Tag\Collection\Assembler\TagToDtoV1ResultAssembler;
use App\Application\Tag\Tag\Dto\CreateTagV1RequestDto;
use App\Application\Tag\Tag\Dto\CreateTagV1ResultDto;
use App\Domain\Entity\Tag;

class CreateTagV1ResultAssembler
{
    private TagToDtoV1ResultAssembler $tagToDtoV1ResultAssembler;

    public function __construct(TagToDtoV1ResultAssembler $tagToDtoV1ResultAssembler)
    {
        $this->tagToDtoV1ResultAssembler = $tagToDtoV1ResultAssembler;
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
