<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\Tag\Dto\CreateTagV1RequestDto;
use App\Application\Tag\Dto\CreateTagV1ResultDto;
use App\Application\Tag\Assembler\CreateTagV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\TagServiceInterface;

class TagService
{
    private CreateTagV1ResultAssembler $createTagV1ResultAssembler;
    private TagServiceInterface $tagService;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        CreateTagV1ResultAssembler $createTagV1ResultAssembler,
        TagServiceInterface $tagService,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->createTagV1ResultAssembler = $createTagV1ResultAssembler;
        $this->tagService = $tagService;
        $this->accountAccessService = $accountAccessService;
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
}
