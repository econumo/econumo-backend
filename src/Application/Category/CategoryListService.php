<?php

declare(strict_types=1);

namespace App\Application\Category;

use App\Application\Category\Dto\GetCategoryListV1RequestDto;
use App\Application\Category\Dto\GetCategoryListV1ResultDto;
use App\Application\Category\Assembler\GetCategoryListV1ResultAssembler;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;

class CategoryListService
{
    private GetCategoryListV1ResultAssembler $getCategoryListV1ResultAssembler;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        GetCategoryListV1ResultAssembler $getCategoryListV1ResultAssembler,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->getCategoryListV1ResultAssembler = $getCategoryListV1ResultAssembler;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryList(
        GetCategoryListV1RequestDto $dto,
        Id $userId
    ): GetCategoryListV1ResultDto {
        $categories = $this->categoryRepository->findByUserId($userId);
        return $this->getCategoryListV1ResultAssembler->assemble($dto, $categories);
    }
}
