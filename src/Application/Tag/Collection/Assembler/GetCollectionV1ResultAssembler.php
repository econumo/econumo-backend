<?php

declare(strict_types=1);

namespace App\Application\Tag\Collection\Assembler;

use App\Application\Tag\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Tag\Collection\Dto\GetCollectionV1ResultDto;
use App\Application\Tag\Collection\Dto\TagResultDto;
use App\Domain\Entity\Tag;

class GetCollectionV1ResultAssembler
{
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
            $item = new TagResultDto();
            $item->id = $tag->getId()->getValue();
            $item->name = $tag->getName();
            $item->position = $tag->getPosition();
            $item->ownerId = $tag->getUserId()->getValue();
            $item->isArchived = $tag->isArchived() ? 1 : 0;
            $result->items[] = $item;
        }

        return $result;
    }
}
