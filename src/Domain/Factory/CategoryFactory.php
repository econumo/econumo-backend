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
    private DatetimeServiceInterface $datetimeService;

    private UserRepositoryInterface $userRepository;

    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        UserRepositoryInterface $userRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->datetimeService = $datetimeService;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
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
