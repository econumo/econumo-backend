<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\TagResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;

class TagIdToDtoResultAssembler
{
    private TagRepositoryInterface $tagRepository;

    private TagToDtoResultAssembler $tagToDtoResultAssembler;

    public function __construct(TagRepositoryInterface $tagRepository, TagToDtoResultAssembler $tagToDtoResultAssembler)
    {
        $this->tagRepository = $tagRepository;
        $this->tagToDtoResultAssembler = $tagToDtoResultAssembler;
    }

    public function assemble(Id $tagId): TagResultDto
    {
        $tag = $this->tagRepository->get($tagId);
        return $this->tagToDtoResultAssembler->assemble($tag);
    }
}
