<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Application\Category\Assembler\CreateCategoryV1ResultAssembler;
use App\Application\Category\Assembler\DeleteCategoryV1ResultAssembler;
use App\Application\Category\Dto\CreateCategoryV1RequestDto;
use App\Application\Category\Dto\CreateCategoryV1ResultDto;
use App\Application\Category\Dto\DeleteCategoryV1RequestDto;
use App\Application\Category\Dto\DeleteCategoryV1ResultDto;
use App\Application\Category\Dto\UpdateCategoryV1RequestDto;
use App\Application\Category\Dto\UpdateCategoryV1ResultDto;
use App\Application\Category\Assembler\UpdateCategoryV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Service\AccountAccessServiceInterface;
use App\Domain\Service\CategoryServiceInterface;

class CategoryService
{
    private CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler;
    private CategoryServiceInterface $categoryService;
    private AccountAccessServiceInterface $accountAccessService;
    private DeleteCategoryV1ResultAssembler $deleteCategoryV1ResultAssembler;
    private CategoryRepositoryInterface $categoryRepository;
    private UpdateCategoryV1ResultAssembler $updateCategoryV1ResultAssembler;

    public function __construct(
        CreateCategoryV1ResultAssembler $createCategoryV1ResultAssembler,
        CategoryServiceInterface $categoryService,
        AccountAccessServiceInterface $accountAccessService,
        DeleteCategoryV1ResultAssembler $deleteCategoryV1ResultAssembler,
        CategoryRepositoryInterface $categoryRepository,
        UpdateCategoryV1ResultAssembler $updateCategoryV1ResultAssembler
    ) {
        $this->createCategoryV1ResultAssembler = $createCategoryV1ResultAssembler;
        $this->categoryService = $categoryService;
        $this->accountAccessService = $accountAccessService;
        $this->deleteCategoryV1ResultAssembler = $deleteCategoryV1ResultAssembler;
        $this->categoryRepository = $categoryRepository;
        $this->updateCategoryV1ResultAssembler = $updateCategoryV1ResultAssembler;
    }

    public function createCategory(
        CreateCategoryV1RequestDto $dto,
        Id $userId
    ): CreateCategoryV1ResultDto {
        $icon = new Icon(!empty($dto->icon) ? $dto->icon : 'local_offer');
        if ($dto->accountId !== null) {
            $accountId = new Id($dto->accountId);
            $this->accountAccessService->checkAddCategory($userId, $accountId);
            $category = $this->categoryService->createCategoryForAccount(
                $userId,
                $accountId,
                $dto->name,
                CategoryType::createFromAlias($dto->type),
                $icon
            );
        } else {
            $category = $this->categoryService->createCategory(
                $userId,
                $dto->name,
                CategoryType::createFromAlias($dto->type),
                $icon
            );
        }

        return $this->createCategoryV1ResultAssembler->assemble($dto, $category);
    }

    public function deleteCategory(
        DeleteCategoryV1RequestDto $dto,
        Id $userId
    ): DeleteCategoryV1ResultDto {
        $categoryId = new Id($dto->id);
        $category = $this->categoryRepository->get($categoryId);
        if (!$category->getUserId()->isEqual($userId)) {
            throw new ValidationException('Category not found');
        }

        if ($dto->mode === $dto::MODE_DELETE) {
            $this->categoryService->deleteCategory($categoryId);
        } elseif ($dto->mode === $dto::MODE_REPLACE) {
            $this->categoryService->replaceCategory($categoryId, new Id($dto->replaceId));
        } else {
            throw new ValidationException('Unknown action');
        }
        return $this->deleteCategoryV1ResultAssembler->assemble($dto);
    }

    public function updateCategory(
        UpdateCategoryV1RequestDto $dto,
        Id $userId
    ): UpdateCategoryV1ResultDto {
        $categoryId = new Id($dto->id);
        $category = $this->categoryRepository->get($categoryId);
        if (!$category->getUserId()->isEqual($userId)) {
            throw new ValidationException('Category not found');
        }

        $this->categoryService->update($categoryId, (bool)$dto->isArchived, $dto->name, new Icon($dto->icon));
        return $this->updateCategoryV1ResultAssembler->assemble($dto);
    }
}
