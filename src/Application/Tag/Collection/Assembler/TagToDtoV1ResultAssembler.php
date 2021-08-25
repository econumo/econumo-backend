<?php

declare(strict_types=1);

namespace App\Application\Tag\Collection\Assembler;

use App\Application\Tag\Collection\Dto\TagResultDto;
use App\Domain\Entity\Tag;

class TagToDtoV1ResultAssembler
{
    public function assemble(Tag $tag): TagResultDto
    {
        $item = new TagResultDto();
        $item->id = $tag->getId()->getValue();
        $item->name = $tag->getName();
        $item->position = $tag->getPosition();
        $item->ownerId = $tag->getUserId()->getValue();
        $item->isArchived = $tag->isArchived() ? 1 : 0;
        return $item;
    }
}
