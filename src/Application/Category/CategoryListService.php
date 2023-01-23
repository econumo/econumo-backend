<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Application\Category\Assembler\GetCategoryListV1ResultAssembler;
use App\Application\Category\Dto\GetCategoryListV1RequestDto;
use App\Application\Category\Dto\GetCategoryListV1ResultDto;
use App\Application\Category\Dto\OrderCategoryListV1RequestDto;
use App\Application\Category\Dto\OrderCategoryListV1ResultDto;
use App\Application\Category\Assembler\OrderCategoryListV1ResultAssembler;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Service\CategoryServiceInterface;
use App\Domain\Service\Translation\TranslationServiceInterface;

class CategoryListService
{
    private GetCategoryListV1ResultAssembler $getCategoryListV1ResultAssembler;

    private CategoryRepositoryInterface $categoryRepository;

    private OrderCategoryListV1ResultAssembler $orderCategoryListV1ResultAssembler;

    private CategoryServiceInterface $categoryService;

    private TranslationServiceInterface $translationService;

    public function __construct(
        GetCategoryListV1ResultAssembler $getCategoryListV1ResultAssembler,
        CategoryRepositoryInterface $categoryRepository,
        OrderCategoryListV1ResultAssembler $orderCategoryListV1ResultAssembler,
        CategoryServiceInterface $categoryService,
        TranslationServiceInterface $translationService
    ) {
        $this->getCategoryListV1ResultAssembler = $getCategoryListV1ResultAssembler;
        $this->categoryRepository = $categoryRepository;
        $this->orderCategoryListV1ResultAssembler = $orderCategoryListV1ResultAssembler;
        $this->categoryService = $categoryService;
        $this->translationService = $translationService;
    }

    public function getCategoryList(
        GetCategoryListV1RequestDto $dto,
        Id $userId
    ): GetCategoryListV1ResultDto {
        $categories = $this->categoryRepository->findAvailableForUserId($userId);
        return $this->getCategoryListV1ResultAssembler->assemble($dto, $categories);
    }

    public function orderCategoryList(
        OrderCategoryListV1RequestDto $dto,
        Id $userId
    ): OrderCategoryListV1ResultDto {
        if ($dto->changes === []) {
            throw new ValidationException($this->translationService->trans('category.category_list.empty_list'));
        }

        $this->categoryService->orderCategories($userId, $dto->changes);
        return $this->orderCategoryListV1ResultAssembler->assemble($dto, $userId);
    }
}
