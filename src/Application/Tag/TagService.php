<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\Exception\AccessDeniedException;
use App\Application\Exception\ValidationException;
use App\Application\Tag\Assembler\ArchiveTagV1ResultAssembler;
use App\Application\Tag\Assembler\CreateTagV1ResultAssembler;
use App\Application\Tag\Assembler\DeleteTagV1ResultAssembler;
use App\Application\Tag\Assembler\UpdateTagV1ResultAssembler;
use App\Application\Tag\Dto\ArchiveTagV1RequestDto;
use App\Application\Tag\Dto\ArchiveTagV1ResultDto;
use App\Application\Tag\Dto\CreateTagV1RequestDto;
use App\Application\Tag\Dto\CreateTagV1ResultDto;
use App\Application\Tag\Dto\DeleteTagV1RequestDto;
use App\Application\Tag\Dto\DeleteTagV1ResultDto;
use App\Application\Tag\Dto\UnarchiveTagV1RequestDto;
use App\Application\Tag\Dto\UnarchiveTagV1ResultDto;
use App\Application\Tag\Assembler\UnarchiveTagV1ResultAssembler;
use App\Application\Tag\Dto\UpdateTagV1RequestDto;
use App\Application\Tag\Dto\UpdateTagV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TagName;
use App\Domain\Exception\TagAlreadyExistsException;
use App\Domain\Repository\TagRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\TagServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

class TagService
{
    public function __construct(private readonly CreateTagV1ResultAssembler $createTagV1ResultAssembler, private readonly TagServiceInterface $tagService, private readonly AccountAccessServiceInterface $accountAccessService, private readonly UpdateTagV1ResultAssembler $updateTagV1ResultAssembler, private readonly TagRepositoryInterface $tagRepository, private readonly DeleteTagV1ResultAssembler $deleteTagV1ResultAssembler, private readonly ArchiveTagV1ResultAssembler $archiveTagV1ResultAssembler, private readonly UnarchiveTagV1ResultAssembler $unarchiveTagV1ResultAssembler, private readonly TranslationServiceInterface $translationService)
    {
    }

    public function createTag(
        CreateTagV1RequestDto $dto,
        Id $userId
    ): CreateTagV1ResultDto {
        try {
            if ($dto->accountId !== null) {
                $accountId = new Id($dto->accountId);
                $this->accountAccessService->checkAddTag($userId, $accountId);
                $tag = $this->tagService->createTagForAccount($userId, $accountId, new TagName($dto->name));
            } else {
                $tag = $this->tagService->createTag($userId, new TagName($dto->name));
            }
        } catch (TagAlreadyExistsException) {
            throw new ValidationException($this->translationService->trans('tag.tag.already_exists', ['name' => $dto->name]));
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
            throw new AccessDeniedException();
        }

        try {
            $this->tagService->updateTag($tagId, new TagName($dto->name));
        } catch (TagAlreadyExistsException) {
            throw new ValidationException($this->translationService->trans('tag.tag.already_exists', ['name' => $dto->name]));
        }

        return $this->updateTagV1ResultAssembler->assemble($dto);
    }

    public function deleteTag(
        DeleteTagV1RequestDto $dto,
        Id $userId
    ): DeleteTagV1ResultDto {
        $tagId = new Id($dto->id);
        $tag = $this->tagRepository->get($tagId);
        if (!$tag->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->tagService->deleteTag($tagId);
        return $this->deleteTagV1ResultAssembler->assemble($dto);
    }

    public function archiveTag(
        ArchiveTagV1RequestDto $dto,
        Id $userId
    ): ArchiveTagV1ResultDto {
        $tagId = new Id($dto->id);
        $tag = $this->tagRepository->get($tagId);
        if (!$tag->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->tagService->archiveTag($tagId);
        return $this->archiveTagV1ResultAssembler->assemble($dto);
    }

    public function unarchiveTag(
        UnarchiveTagV1RequestDto $dto,
        Id $userId
    ): UnarchiveTagV1ResultDto {
        $tagId = new Id($dto->id);
        $tag = $this->tagRepository->get($tagId);
        if (!$tag->getUserId()->isEqual($userId)) {
            throw new AccessDeniedException();
        }

        $this->tagService->unarchiveTag($tagId);
        return $this->unarchiveTagV1ResultAssembler->assemble($dto);
    }
}
