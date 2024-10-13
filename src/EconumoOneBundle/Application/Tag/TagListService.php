<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag;

use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Application\Tag\Assembler\GetTagListV1ResultAssembler;
use App\EconumoOneBundle\Application\Tag\Dto\GetTagListV1RequestDto;
use App\EconumoOneBundle\Application\Tag\Dto\GetTagListV1ResultDto;
use App\EconumoOneBundle\Application\Tag\Dto\OrderTagListV1RequestDto;
use App\EconumoOneBundle\Application\Tag\Dto\OrderTagListV1ResultDto;
use App\EconumoOneBundle\Application\Tag\Assembler\OrderTagListV1ResultAssembler;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\TagRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\TagServiceInterface;
use App\EconumoOneBundle\Domain\Service\Translation\TranslationServiceInterface;

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
