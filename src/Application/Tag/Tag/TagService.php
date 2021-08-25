<?php

declare(strict_types=1);

namespace App\Application\Tag\Tag;

use App\Application\Tag\Tag\Dto\CreateTagV1RequestDto;
use App\Application\Tag\Tag\Dto\CreateTagV1ResultDto;
use App\Application\Tag\Tag\Assembler\CreateTagV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\TagServiceInterface;

class TagService
{
    private CreateTagV1ResultAssembler $createTagV1ResultAssembler;
    private TagServiceInterface $tagService;

    public function __construct(
        CreateTagV1ResultAssembler $createTagV1ResultAssembler,
        TagServiceInterface $tagService
    ) {
        $this->createTagV1ResultAssembler = $createTagV1ResultAssembler;
        $this->tagService = $tagService;
    }

    public function createTag(
        CreateTagV1RequestDto $dto,
        Id $userId
    ): CreateTagV1ResultDto {
        $tag = $this->tagService->createTag($userId, new Id($dto->id), $dto->name);
        return $this->createTagV1ResultAssembler->assemble($dto, $tag);
    }
}
