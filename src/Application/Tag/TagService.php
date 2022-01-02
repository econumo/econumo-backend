<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\Exception\ValidationException;
use App\Application\Tag\Assembler\CreateTagV1ResultAssembler;
use App\Application\Tag\Dto\CreateTagV1RequestDto;
use App\Application\Tag\Dto\CreateTagV1ResultDto;
use App\Application\Tag\Dto\UpdateTagV1RequestDto;
use App\Application\Tag\Dto\UpdateTagV1ResultDto;
use App\Application\Tag\Assembler\UpdateTagV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\TagServiceInterface;

class TagService
{
    private CreateTagV1ResultAssembler $createTagV1ResultAssembler;
    private TagServiceInterface $tagService;
    private AccountAccessServiceInterface $accountAccessService;
    private UpdateTagV1ResultAssembler $updateTagV1ResultAssembler;
    private TagRepositoryInterface $tagRepository;

    public function __construct(
        CreateTagV1ResultAssembler $createTagV1ResultAssembler,
        TagServiceInterface $tagService,
        AccountAccessServiceInterface $accountAccessService,
        UpdateTagV1ResultAssembler $updateTagV1ResultAssembler,
        TagRepositoryInterface $tagRepository
    ) {
        $this->createTagV1ResultAssembler = $createTagV1ResultAssembler;
        $this->tagService = $tagService;
        $this->accountAccessService = $accountAccessService;
        $this->updateTagV1ResultAssembler = $updateTagV1ResultAssembler;
        $this->tagRepository = $tagRepository;
    }

    public function createTag(
        CreateTagV1RequestDto $dto,
        Id $userId
    ): CreateTagV1ResultDto {
        if ($dto->accountId !== null) {
            $accountId = new Id($dto->accountId);
            $this->accountAccessService->checkAddTag($userId, $accountId);
            $tag = $this->tagService->createTagForAccount($userId, $accountId, $dto->name);
        } else {
            $tag = $this->tagService->createTag($userId, $dto->name);
        }

        return $this->createTagV1ResultAssembler->assemble($dto, $tag);
    }

    public function updateTag(
        UpdateTagV1RequestDto $dto,
        Id $userId
    ): UpdateTagV1ResultDto {
        $tagId = new Id($dto->id);
        $tag = $this->tagRepository->get($tagId);
        if (!$tag->getUserId()->isEqual($userId)) {
            throw new ValidationException('Tag is not valid');
        }
        $this->tagService->updateTag($tagId, $dto->name, (bool)$dto->isArchived);
        return $this->updateTagV1ResultAssembler->assemble($dto);
    }
}
