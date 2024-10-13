<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Assembler\UserCategoryToDtoResultAssembler;
use App\EconumoOneBundle\Application\Category\Dto\OrderCategoryListV1RequestDto;
use App\EconumoOneBundle\Application\Category\Dto\OrderCategoryListV1ResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CategoryRepositoryInterface;

class OrderCategoryListV1ResultAssembler
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository, private readonly UserCategoryToDtoResultAssembler $categoryToDtoResultAssembler)
    {
    }

    public function assemble(
        OrderCategoryListV1RequestDto $dto,
        Id $userId
    ): OrderCategoryListV1ResultDto {
        $result = new OrderCategoryListV1ResultDto();
        $categories = $this->categoryRepository->findAvailableForUserId($userId);
        $result->items = [];
        foreach ($categories as $category) {
            $result->items[] = $this->categoryToDtoResultAssembler->assemble($category);
        }

        return $result;
    }
}
