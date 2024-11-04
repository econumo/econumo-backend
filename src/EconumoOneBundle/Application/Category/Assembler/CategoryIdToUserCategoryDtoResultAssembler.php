<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Assembler;

use App\EconumoOneBundle\Application\Category\Assembler\UserCategoryToDtoResultAssembler;
use App\EconumoOneBundle\Application\Category\Dto\CategoryResultDto;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CategoryRepositoryInterface;

class CategoryIdToUserCategoryDtoResultAssembler
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository, private readonly UserCategoryToDtoResultAssembler $categoryToDtoResultAssembler)
    {
    }

    public function assemble(Id $categoryId): CategoryResultDto
    {
        $category = $this->categoryRepository->get($categoryId);
        return $this->categoryToDtoResultAssembler->assemble($category);
    }
}
