<?php

declare(strict_types=1);

namespace App\Application\Category\Collection\Assembler;

use App\Application\Category\Collection\Dto\CategoryResultDto;
use App\Application\Category\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Category\Collection\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Category;

class GetCollectionV1ResultAssembler
{
    private CategoryToDtoV1ResultAssembler $categoryToDtoV1ResultAssembler;

    public function __construct(CategoryToDtoV1ResultAssembler $categoryToDtoV1ResultAssembler)
    {
        $this->categoryToDtoV1ResultAssembler = $categoryToDtoV1ResultAssembler;
    }

    /**
     * @param GetCollectionV1RequestDto $dto
     * @param Category[] $categories
     * @return GetCollectionV1ResultDto
     */
    public function assemble(
        GetCollectionV1RequestDto $dto,
        array $categories
    ): GetCollectionV1ResultDto {
        $result = new GetCollectionV1ResultDto();
        $result->items = [];
        foreach ($categories as $category) {
            $result->items[] = $this->categoryToDtoV1ResultAssembler->assemble($category);
        }

        return $result;
    }
}
