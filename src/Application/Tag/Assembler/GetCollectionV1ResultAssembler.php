<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\GetCollectionV1RequestDto;
use App\Application\Tag\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Tag;

class GetCollectionV1ResultAssembler
{
    private TagToDtoV1ResultAssembler $tagToDtoV1ResultAssembler;

    public function __construct(TagToDtoV1ResultAssembler $tagToDtoV1ResultAssembler)
    {
        $this->tagToDtoV1ResultAssembler = $tagToDtoV1ResultAssembler;
    }

    /**
     * @param GetCollectionV1RequestDto $dto
     * @param Tag[] $tags
     * @return GetCollectionV1ResultDto
     */
    public function assemble(
        GetCollectionV1RequestDto $dto,
        array $tags
    ): GetCollectionV1ResultDto {
        $result = new GetCollectionV1ResultDto();
        $result->items = [];
        foreach ($tags as $tag) {
            $result->items[] = $this->tagToDtoV1ResultAssembler->assemble($tag);
        }

        return $result;
    }
}
