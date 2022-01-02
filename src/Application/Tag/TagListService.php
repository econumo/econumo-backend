<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\Exception\ValidationException;
use App\Application\Tag\Assembler\GetTagListV1ResultAssembler;
use App\Application\Tag\Dto\GetTagListV1RequestDto;
use App\Application\Tag\Dto\GetTagListV1ResultDto;
use App\Application\Tag\Dto\OrderTagListV1RequestDto;
use App\Application\Tag\Dto\OrderTagListV1ResultDto;
use App\Application\Tag\Assembler\OrderTagListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\TagServiceInterface;

class TagListService
{
    private GetTagListV1ResultAssembler $getTagListV1ResultAssembler;
    private TagRepositoryInterface $tagRepository;
    private OrderTagListV1ResultAssembler $orderTagListV1ResultAssembler;
    private TagServiceInterface $tagService;

    public function __construct(
        GetTagListV1ResultAssembler $getTagListV1ResultAssembler,
        TagRepositoryInterface $tagRepository,
        OrderTagListV1ResultAssembler $orderTagListV1ResultAssembler,
        TagServiceInterface $tagService
    ) {
        $this->getTagListV1ResultAssembler = $getTagListV1ResultAssembler;
        $this->tagRepository = $tagRepository;
        $this->orderTagListV1ResultAssembler = $orderTagListV1ResultAssembler;
        $this->tagService = $tagService;
    }

    public function getTagList(
        GetTagListV1RequestDto $dto,
        Id $userId
    ): GetTagListV1ResultDto {
        $tags = $this->tagRepository->findByUserId($userId);
        return $this->getTagListV1ResultAssembler->assemble($dto, $tags);
    }

    public function orderTagList(
        OrderTagListV1RequestDto $dto,
        Id $userId
    ): OrderTagListV1ResultDto {
        $tags = $this->tagRepository->findByUserId($userId);
        $orderedList = [];
        foreach ($dto->ids as $id) {
            $tagId = new Id($id);
            foreach ($tags as $tag) {
                if ($tag->getId()->isEqual($tagId)) {
                    $orderedList[] = $tagId;
                    break;
                }
            }
        }
        if (!count($orderedList)) {
            throw new ValidationException('tag list is empty');
        }
        $this->tagService->orderTags($userId, ...$orderedList);

        return $this->orderTagListV1ResultAssembler->assemble($dto, $userId);
    }
}
