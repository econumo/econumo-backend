<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\GetTagListV1RequestDto;
use App\Application\Tag\Dto\GetTagListV1ResultDto;
use App\Domain\Entity\Tag;

class GetTagListV1ResultAssembler
{
    private TagToDtoResultAssembler $tagToDtoV1ResultAssembler;

    public function __construct(TagToDtoResultAssembler $tagToDtoV1ResultAssembler)
    {
        $this->tagToDtoV1ResultAssembler = $tagToDtoV1ResultAssembler;
    }

    /**
     * @param GetTagListV1RequestDto $dto
     * @param Tag[] $tags
     * @return GetTagListV1ResultDto
     */
    public function assemble(
        GetTagListV1RequestDto $dto,
        array $tags
    ): GetTagListV1ResultDto {
        $result = new GetTagListV1ResultDto();
        $result->items = [];
        foreach ($tags as $tag) {
            $result->items[] = $this->tagToDtoV1ResultAssembler->assemble($tag);
        }

        return $result;
    }
}
