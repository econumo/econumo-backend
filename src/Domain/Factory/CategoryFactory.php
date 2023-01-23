<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryName;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class CategoryFactory implements CategoryFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly UserRepositoryInterface $userRepository, private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function create(Id $userId, CategoryName $name, CategoryType $type, Icon $icon): Category
    {
        return new Category(
            $this->categoryRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $type,
            $icon,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
