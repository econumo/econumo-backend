<?php

declare(strict_types=1);

namespace App\Application\Tag\Assembler;

use App\Application\Tag\Dto\OrderTagListV1RequestDto;
use App\Application\Tag\Dto\OrderTagListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;

class OrderTagListV1ResultAssembler
{
    private TagRepositoryInterface $tagRepository;
    private TagToDtoResultAssembler $tagToDtoResultAssembler;

    public function __construct(TagRepositoryInterface $tagRepository, TagToDtoResultAssembler $tagToDtoResultAssembler)
    {
        $this->tagRepository = $tagRepository;
        $this->tagToDtoResultAssembler = $tagToDtoResultAssembler;
    }

    public function assemble(
        OrderTagListV1RequestDto $dto,
        Id $userId
    ): OrderTagListV1ResultDto {
        $result = new OrderTagListV1ResultDto();
        $tags = $this->tagRepository->findByUserId($userId);
        $result->items = [];
        foreach ($tags as $tag) {
            $result->items[] = $this->tagToDtoResultAssembler->assemble($tag);
        }

        return $result;
    }
}
