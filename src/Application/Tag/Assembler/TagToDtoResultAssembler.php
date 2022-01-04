<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\TagResultDto;
use App\Domain\Entity\Tag;

class TagToDtoResultAssembler
{
    public function assemble(Tag $tag): TagResultDto
    {
        $item = new TagResultDto();
        $item->id = $tag->getId()->getValue();
        $item->name = $tag->getName();
        $item->position = $tag->getPosition();
        $item->ownerUserId = $tag->getUserId()->getValue();
        $item->isArchived = $tag->isArchived() ? 1 : 0;
        $item->createdAt = $tag->getCreatedAt()->format('Y-m-d H:i:s');
        $item->updatedAt = $tag->getUpdatedAt()->format('Y-m-d H:i:s');
        return $item;
    }
}
