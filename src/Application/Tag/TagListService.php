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
use App\Domain\Service\Translation\TranslationServiceInterface;

class TagListService
{
    private GetTagListV1ResultAssembler $getTagListV1ResultAssembler;

    private TagRepositoryInterface $tagRepository;

    private OrderTagListV1ResultAssembler $orderTagListV1ResultAssembler;

    private TagServiceInterface $tagService;

    private TranslationServiceInterface $translationService;

    public function __construct(
        GetTagListV1ResultAssembler $getTagListV1ResultAssembler,
        TagRepositoryInterface $tagRepository,
        OrderTagListV1ResultAssembler $orderTagListV1ResultAssembler,
        TagServiceInterface $tagService,
        TranslationServiceInterface $translationService
    ) {
        $this->getTagListV1ResultAssembler = $getTagListV1ResultAssembler;
        $this->tagRepository = $tagRepository;
        $this->orderTagListV1ResultAssembler = $orderTagListV1ResultAssembler;
        $this->tagService = $tagService;
        $this->translationService = $translationService;
    }

    public function getTagList(
        GetTagListV1RequestDto $dto,
        Id $userId
    ): GetTagListV1ResultDto {
        $tags = $this->tagRepository->findAvailableForUserId($userId);
        return $this->getTagListV1ResultAssembler->assemble($dto, $tags);
    }

    public function orderTagList(
        OrderTagListV1RequestDto $dto,
        Id $userId
    ): OrderTagListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('tag.tag_list.empty_list'));
        }

        $this->tagService->orderTags($userId, $dto->changes);

        return $this->orderTagListV1ResultAssembler->assemble($dto, $userId);
    }
}
