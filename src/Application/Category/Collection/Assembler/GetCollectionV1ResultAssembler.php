<?php

declare(strict_types=1);

namespace App\Application\Category\Collection\Assembler;

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
        $result->items = [];
        foreach ($categories as $category) {
            $item = new CategoryResultDto();
            $item->id = $category->getId()->getValue();
            $item->name = $category->getName();
            $item->position = $category->getPosition();
            $item->type = $category->getType()->getAlias();
            $item->accountId = null; // @todo return not null if shared account
            $result->items[] = $item;
        }

        return $result;
    }
}
