<?php

declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\CategoryResultDto;
use App\Domain\Entity\Category;

class CategoryToDtoV1ResultAssembler
{
    public function assemble(Category $category): CategoryResultDto
    {
        $item = new CategoryResultDto();
        $item->id = $category->getId()->getValue();
        $item->name = $category->getName();
        $item->position = $category->getPosition();
        $item->type = $category->getType()->getAlias();
        $item->ownerId = $category->getUserId()->getValue();
        return $item;
    }
}
