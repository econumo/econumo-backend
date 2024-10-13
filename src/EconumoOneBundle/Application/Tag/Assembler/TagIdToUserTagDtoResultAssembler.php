<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Assembler;

use App\EconumoOneBundle\Application\Tag\Dto\TagResultDto;
use App\EconumoOneBundle\Application\Tag\Assembler\TagToUserTagDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\TagRepositoryInterface;

readonly class TagIdToUserTagDtoResultAssembler
{
    public function __construct(private TagRepositoryInterface $tagRepository, private TagToUserTagDtoResultAssembler $tagToDtoResultAssembler)
    {
    }

    public function assemble(Id $tagId): TagResultDto
    {
        $tag = $this->tagRepository->get($tagId);
        return $this->tagToDtoResultAssembler->assemble($tag);
    }
}
