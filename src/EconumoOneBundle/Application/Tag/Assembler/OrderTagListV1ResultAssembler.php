<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Assembler;

use App\EconumoOneBundle\Application\Tag\Dto\OrderTagListV1RequestDto;
use App\EconumoOneBundle\Application\Tag\Dto\OrderTagListV1ResultDto;
use App\EconumoOneBundle\Application\Tag\Assembler\TagToUserTagDtoResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\TagRepositoryInterface;

class OrderTagListV1ResultAssembler
{
    public function __construct(private readonly TagRepositoryInterface $tagRepository, private readonly TagToUserTagDtoResultAssembler $tagToDtoResultAssembler)
    {
    }

    public function assemble(
        OrderTagListV1RequestDto $dto,
        Id $userId
    ): OrderTagListV1ResultDto {
        $result = new OrderTagListV1ResultDto();
        $tags = $this->tagRepository->findAvailableForUserId($userId);
        $result->items = [];
        foreach ($tags as $tag) {
            $result->items[] = $this->tagToDtoResultAssembler->assemble($tag);
        }

        return $result;
    }
}
