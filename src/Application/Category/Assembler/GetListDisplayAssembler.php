<?php
declare(strict_types=1);

namespace App\Application\Category\Assembler;

use App\Application\Category\Dto\CategoryDisplayDto;
use App\Application\Category\Dto\CategoryPermissionsDisplayDto;
use App\Application\Category\Dto\GetListDisplayDto;
use App\Domain\Entity\Category;

class GetListDisplayAssembler
{
    /**
     * @param Category[] $categories
     * @return GetListDisplayDto
     */
    public function assemble(array $categories): GetListDisplayDto
    {
        $dto = new GetListDisplayDto();
        $dto->items = [];
        foreach (array_reverse($categories) as $category) {
            $item = new CategoryDisplayDto();
            $item->id = $category->getId()->getValue();
            $item->name = $category->getName();
            $item->level = $category->getLevel();
            $item->position = $category->getPosition();
            $item->isIncome = $category->isIncome();
            $permissions = new CategoryPermissionsDisplayDto();
            $permissions->canEdit = true;
            $item->permissions = $permissions;
            $dto->items[] = $item;
        }

        return $dto;
    }
}
