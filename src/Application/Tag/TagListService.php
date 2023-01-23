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
    public function __construct(private readonly GetTagListV1ResultAssembler $getTagListV1ResultAssembler, private readonly TagRepositoryInterface $tagRepository, private readonly OrderTagListV1ResultAssembler $orderTagListV1ResultAssembler, private readonly TagServiceInterface $tagService, private readonly TranslationServiceInterface $translationService)
    {
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
