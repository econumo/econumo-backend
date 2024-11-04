<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Assembler;

use App\EconumoOneBundle\Application\Tag\Assembler\TagToUserTagDtoResultAssembler;
use App\EconumoOneBundle\Application\Tag\Dto\GetTagListV1RequestDto;
use App\EconumoOneBundle\Application\Tag\Dto\GetTagListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Tag;

readonly class GetTagListV1ResultAssembler
{
    public function __construct(private TagToUserTagDtoResultAssembler $tagToDtoV1ResultAssembler)
    {
    }

    /**
     * @param Tag[] $tags
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
