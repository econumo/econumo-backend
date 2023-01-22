<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\CategoryResultDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;

class CategoryIdToDtoResultAssembler
{
    private CategoryRepositoryInterface $categoryRepository;

    private CategoryToDtoResultAssembler $categoryToDtoResultAssembler;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryToDtoResultAssembler $categoryToDtoResultAssembler
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryToDtoResultAssembler = $categoryToDtoResultAssembler;
    }

    public function assemble(Id $categoryId): CategoryResultDto
    {
        $category = $this->categoryRepository->get($categoryId);
        return $this->categoryToDtoResultAssembler->assemble($category);
    }
}
