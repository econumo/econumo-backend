<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\Tag\Dto\GetTagListV1RequestDto;
use App\Application\Tag\Dto\GetTagListV1ResultDto;
use App\Application\Tag\Assembler\GetTagListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;

class TagListService
{
    private GetTagListV1ResultAssembler $getTagListV1ResultAssembler;
    private TagRepositoryInterface $tagRepository;

    public function __construct(
        GetTagListV1ResultAssembler $getTagListV1ResultAssembler,
        TagRepositoryInterface $tagRepository
    ) {
        $this->getTagListV1ResultAssembler = $getTagListV1ResultAssembler;
        $this->tagRepository = $tagRepository;
    }

    public function getTagList(
        GetTagListV1RequestDto $dto,
        Id $userId
    ): GetTagListV1ResultDto {
        $tags = $this->tagRepository->findByUserId($userId);
        return $this->getTagListV1ResultAssembler->assemble($dto, $tags);
    }
}
