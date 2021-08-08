<?php

declare(strict_types=1);

namespace App\Application\Category\Collection\Assembler;

use App\Application\Category\Collection\Dto\CategoryPermissionsResultDto;
use App\Application\Category\Collection\Dto\CategoryResultDto;
use App\Application\Category\Collection\Dto\GetCollectionV1RequestDto;
use App\Application\Category\Collection\Dto\GetCollectionV1ResultDto;
use App\Domain\Entity\Category;

class GetCollectionV1ResultAssembler
{
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
        $dto->items = [];
        foreach (array_reverse($categories) as $category) {
            $item = new CategoryResultDto();
            $item->id = $category->getId()->getValue();
            $item->name = $category->getName();
            $item->level = $category->getLevel();
            $item->position = $category->getPosition();
            $item->isIncome = $category->isIncome();
            $permissions = new CategoryPermissionsResultDto();
            $permissions->canEdit = true;
            $item->permissions = $permissions;
            $dto->items[] = $item;
        }

        return $result;
    }
}
