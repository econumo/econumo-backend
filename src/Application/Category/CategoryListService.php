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
    public function __construct(private readonly GetCategoryListV1ResultAssembler $getCategoryListV1ResultAssembler, private readonly CategoryRepositoryInterface $categoryRepository, private readonly OrderCategoryListV1ResultAssembler $orderCategoryListV1ResultAssembler, private readonly CategoryServiceInterface $categoryService, private readonly TranslationServiceInterface $translationService)
    {
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
