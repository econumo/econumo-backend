<?php

declare(strict_types=1);

namespace App\Application\Category\Category\Assembler;

use App\Application\Category\Category\Dto\CreateCategoryV1RequestDto;
use App\Application\Category\Category\Dto\CreateCategoryV1ResultDto;
use App\Application\Category\Collection\Assembler\CategoryToDtoV1ResultAssembler;
use App\Domain\Entity\Category;

class CreateCategoryV1ResultAssembler
{
    private CategoryToDtoV1ResultAssembler $categoryToDtoV1ResultAssembler;

    public function __construct(CategoryToDtoV1ResultAssembler $categoryToDtoV1ResultAssembler)
    {
        $this->categoryToDtoV1ResultAssembler = $categoryToDtoV1ResultAssembler;
    }

    public function assemble(
        CreateCategoryV1RequestDto $dto,
        Category $category
    ): CreateCategoryV1ResultDto {
        $result = new CreateCategoryV1ResultDto();
        $result->category = $this->categoryToDtoV1ResultAssembler->assemble($category);

        return $result;
    }
}
