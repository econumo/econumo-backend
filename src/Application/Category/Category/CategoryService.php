<?php

declare(strict_types=1);

namespace App\Application\Category\Category;

use App\Application\Category\Category\Dto\CreateCategoryV1RequestDto;
use App\Application\Category\Category\Dto\CreateCategoryV1ResultDto;
use App\Application\Category\Category\Assembler\CreateCategoryV1ResultAssembler;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\CategoryServiceInterface;

class CategoryService
{
    private CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler;
    private CategoryServiceInterface $categoryService;
    private AccountAccessServiceInterface $accountAccessService;

    public function __construct(
        CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler,
        CategoryServiceInterface $categoryService,
        AccountAccessServiceInterface $accountAccessService
    ) {
        $this->createCategoryV1ResultAssembler = $createCategoryV1ResultAssembler;
        $this->categoryService = $categoryService;
        $this->accountAccessService = $accountAccessService;
    }

    public function createCategory(
        CreateCategoryV1RequestDto $dto,
        Id $userId
    ): CreateCategoryV1ResultDto {
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

        return $this->createCategoryV1ResultAssembler->assemble($dto, $category);
    }
}
