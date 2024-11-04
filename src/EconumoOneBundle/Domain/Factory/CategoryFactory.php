<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\CategoryRepositoryInterface;
use App\EconumoOneBundle\Domain\Factory\CategoryFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;

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
