<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\CategoryFactoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    private CategoryFactoryInterface $categoryFactory;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        CategoryFactoryInterface $categoryFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(Id $userId, Id $id, string $name, CategoryType $type): Category
    {
        $category = $this->categoryFactory->create($userId, $id, $name, $type);
        $this->categoryRepository->save($category);

        return $category;
    }
}
