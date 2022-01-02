<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\CategoryFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    private CategoryFactoryInterface $categoryFactory;
    private CategoryRepositoryInterface $categoryRepository;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(
        CategoryFactoryInterface $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->accountRepository = $accountRepository;
    }

    public function createCategory(Id $userId, string $name, CategoryType $type, Icon $icon): Category
    {
        $category = $this->categoryFactory->create($userId, $name, $type, $icon);
        $this->categoryRepository->save($category);

        return $category;
    }

    public function createCategoryForAccount(
        Id $userId,
        Id $accountId,
        string $name,
        CategoryType $type,
        Icon $icon
    ): Category {
        $account = $this->accountRepository->get($accountId);
        if ($userId->isEqual($account->getUserId())) {
            return $this->createCategory($userId, $name, $type, $icon);
        }

        return $this->createCategory($account->getUserId(), $name, $type, $icon);
    }
}
