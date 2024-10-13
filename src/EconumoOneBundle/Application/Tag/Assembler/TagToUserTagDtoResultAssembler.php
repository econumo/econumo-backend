<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Assembler;

use App\EconumoOneBundle\Application\Tag\Dto\TagResultDto;
use App\EconumoOneBundle\Domain\Entity\Tag;

readonly class TagToUserTagDtoResultAssembler
{
    public function assemble(Tag $tag): TagResultDto
    {
        $item = new TagResultDto();
        $item->id = $tag->getId()->getValue();
        $item->name = $tag->getName()->getValue();
        $item->position = $tag->getPosition();
        $item->ownerUserId = $tag->getUserId()->getValue();
        $item->isArchived = $tag->isArchived() ? 1 : 0;
        $item->createdAt = $tag->getCreatedAt()->format('Y-m-d H:i:s');
        $item->updatedAt = $tag->getUpdatedAt()->format('Y-m-d H:i:s');
        return $item;
    }
}
