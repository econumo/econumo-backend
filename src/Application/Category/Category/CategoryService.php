<?php

declare(strict_types=1);

namespace App\Application\Category\Category;

use App\Application\Category\Category\Dto\CreateCategoryV1RequestDto;
use App\Application\Category\Category\Dto\CreateCategoryV1ResultDto;
use App\Application\Category\Category\Assembler\CreateCategoryV1ResultAssembler;
use App\Application\RequestIdLockServiceInterface;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\CategoryServiceInterface;

class CategoryService
{
    private CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler;
    private CategoryServiceInterface $categoryService;
    private AccountAccessServiceInterface $accountAccessService;
    private RequestIdLockServiceInterface $requestIdLockService;

    public function __construct(
        CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler,
        CategoryServiceInterface $categoryService,
        AccountAccessServiceInterface $accountAccessService,
        RequestIdLockServiceInterface $requestIdLockService
    ) {
        $this->createCategoryV1ResultAssembler = $createCategoryV1ResultAssembler;
        $this->categoryService = $categoryService;
        $this->accountAccessService = $accountAccessService;
        $this->requestIdLockService = $requestIdLockService;
    }

    public function createCategory(
        CreateCategoryV1RequestDto $dto,
        Id $userId
    ): CreateCategoryV1ResultDto {
        $requestId = $this->requestIdLockService->register(new Id($dto->id));
        try {
            if ($dto->accountId !== null) {
                $accountId = new Id($dto->accountId);
                $this->accountAccessService->checkAddCategory($userId, $accountId);
                $category = $this->categoryService->createCategoryForAccount(
                    $userId,
                    $accountId,
                    new Id($dto->id),
                    $dto->name,
                    CategoryType::createFromAlias($dto->type)
                );
            } else {
                $category = $this->categoryService->createCategory(
                    $userId,
                    new Id($dto->id),
                    $dto->name,
                    CategoryType::createFromAlias($dto->type)
                );
            }
            $this->requestIdLockService->update($requestId, $category->getId());
        } catch (\Throwable $exception) {
            $this->requestIdLockService->remove($requestId);
            throw $exception;
        }

        return $this->createCategoryV1ResultAssembler->assemble($dto, $category);
    }
}
