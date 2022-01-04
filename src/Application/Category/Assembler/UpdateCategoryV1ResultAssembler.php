<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\UpdateCategoryV1RequestDto;
use App\Application\Category\Dto\UpdateCategoryV1ResultDto;
use App\Domain\Entity\ValueObject\Id;

class UpdateCategoryV1ResultAssembler
{
    private CategoryIdToDtoResultAssembler $categoryIdToDtoResultAssembler;

    public function __construct(CategoryIdToDtoResultAssembler $categoryIdToDtoResultAssembler)
    {
        $this->categoryIdToDtoResultAssembler = $categoryIdToDtoResultAssembler;
    }

    public function assemble(
        UpdateCategoryV1RequestDto $dto
    ): UpdateCategoryV1ResultDto {
        $result = new UpdateCategoryV1ResultDto();
        $result->item = $this->categoryIdToDtoResultAssembler->assemble(new Id($dto->id));

        return $result;
    }
}
