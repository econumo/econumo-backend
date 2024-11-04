<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Assembler\UserCategoryToDtoResultAssembler;
use App\EconumoOneBundle\Application\Category\Dto\GetCategoryListV1RequestDto;
use App\EconumoOneBundle\Application\Category\Dto\GetCategoryListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\Category;

class GetCategoryListV1ResultAssembler
{
    public function __construct(private readonly UserCategoryToDtoResultAssembler $categoryToDtoV1ResultAssembler)
    {
    }

    /**
     * @param Category[] $categories
     */
    public function assemble(
        GetCategoryListV1RequestDto $dto,
        array $categories
    ): GetCategoryListV1ResultDto {
        $result = new GetCategoryListV1ResultDto();
        $result->items = [];
        foreach ($categories as $category) {
            $result->items[] = $this->categoryToDtoV1ResultAssembler->assemble($category);
        }

        return $result;
    }
}
