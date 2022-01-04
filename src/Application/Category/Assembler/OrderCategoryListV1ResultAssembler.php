<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\OrderCategoryListV1RequestDto;
use App\Application\Category\Dto\OrderCategoryListV1ResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;

class OrderCategoryListV1ResultAssembler
{
    private CategoryRepositoryInterface $categoryRepository;
    private CategoryToDtoResultAssembler $categoryToDtoResultAssembler;

    public function __construct(CategoryRepositoryInterface $categoryRepository, CategoryToDtoResultAssembler $categoryToDtoResultAssembler)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryToDtoResultAssembler = $categoryToDtoResultAssembler;
    }

    public function assemble(
        OrderCategoryListV1RequestDto $dto,
        Id $userId
    ): OrderCategoryListV1ResultDto {
        $result = new OrderCategoryListV1ResultDto();
        $categories = $this->categoryRepository->findAvailableForUserId($userId);
        $result->items = [];
        foreach ($categories as $category) {
            $result->items[] = $this->categoryToDtoResultAssembler->assemble($category);
        }

        return $result;
    }
}
