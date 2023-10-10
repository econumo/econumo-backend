<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\UserCategoryResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;

class CategoryIdToDtoResultAssembler
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository, private readonly CategoryToDtoResultAssembler $categoryToDtoResultAssembler)
    {
    }

    public function assemble(Id $categoryId): UserCategoryResultDto
    {
        $category = $this->categoryRepository->get($categoryId);
        return $this->categoryToDtoResultAssembler->assemble($category);
    }
}
