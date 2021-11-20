<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\Tag\Dto\GetCollectionV1RequestDto;
use App\Application\Tag\Dto\GetCollectionV1ResultDto;
use App\Application\Tag\Assembler\GetCollectionV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;

class CollectionService
{
    private GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler;
    private TagRepositoryInterface $tagRepository;

    public function __construct(
        GetCollectionV1ResultAssembler $getCollectionV1ResultAssembler,
        TagRepositoryInterface $tagRepository
    ) {
        $this->getCollectionV1ResultAssembler = $getCollectionV1ResultAssembler;
        $this->tagRepository = $tagRepository;
    }

    public function getCollection(
        GetCollectionV1RequestDto $dto,
        Id $userId
    ): GetCollectionV1ResultDto {
        $tags = $this->tagRepository->findByUserId($userId);
        return $this->getCollectionV1ResultAssembler->assemble($dto, $tags);
    }
}
