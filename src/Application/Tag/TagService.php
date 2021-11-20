<?php

declare(strict_types=1);

namespace App\Application\Tag;

use App\Application\RequestIdLockServiceInterface;
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
    private RequestIdLockServiceInterface $requestIdLockService;

    public function __construct(
        CreateTagV1ResultAssembler $createTagV1ResultAssembler,
        TagServiceInterface $tagService,
        AccountAccessServiceInterface $accountAccessService,
        RequestIdLockServiceInterface $requestIdLockService
    ) {
        $this->createTagV1ResultAssembler = $createTagV1ResultAssembler;
        $this->tagService = $tagService;
        $this->accountAccessService = $accountAccessService;
        $this->requestIdLockService = $requestIdLockService;
    }

    public function createTag(
        CreateTagV1RequestDto $dto,
        Id $userId
    ): CreateTagV1ResultDto {
        $requestId = $this->requestIdLockService->register(new Id($dto->id));
        try {
            if ($dto->accountId !== null) {
                $accountId = new Id($dto->accountId);
                $this->accountAccessService->checkAddTag($userId, $accountId);
                $tag = $this->tagService->createTagForAccount($userId, $accountId, new Id($dto->id), $dto->name);
            } else {
                $tag = $this->tagService->createTag($userId, new Id($dto->id), $dto->name);
            }
            $this->requestIdLockService->update($requestId, $tag->getId());
        } catch (\Throwable $exception) {
            $this->requestIdLockService->remove($requestId);
            throw $exception;
        }

        return $this->createTagV1ResultAssembler->assemble($dto, $tag);
    }
}
