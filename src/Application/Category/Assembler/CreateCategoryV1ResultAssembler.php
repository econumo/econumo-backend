<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\CreateCategoryV1RequestDto;
use App\Application\Category\Dto\CreateCategoryV1ResultDto;
use App\Domain\Entity\Category;

class CreateCategoryV1ResultAssembler
{
    public function __construct(private readonly UserCategoryToDtoResultAssembler $categoryToDtoV1ResultAssembler)
    {
    }

    public function assemble(
        CreateCategoryV1RequestDto $dto,
        Category $category
    ): CreateCategoryV1ResultDto {
        $result = new CreateCategoryV1ResultDto();
        $result->item = $this->categoryToDtoV1ResultAssembler->assemble($category);

        return $result;
    }
}
