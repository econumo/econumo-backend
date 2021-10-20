<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
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

    public function createCategory(Id $userId, Id $id, string $name, CategoryType $type): Category
    {
        $category = $this->categoryFactory->create($userId, $id, $name, $type);
        $this->categoryRepository->save($category);

        return $category;
    }

    public function createCategoryForAccount(
        Id $userId,
        Id $accountId,
        Id $id,
        string $name,
        CategoryType $type
    ): Category {
        $account = $this->accountRepository->get($accountId);
        if ($userId->isEqual($account->getUserId())) {
            return $this->createCategory($userId, $id, $name, $type);
        }

        return $this->createCategory($account->getUserId(), $id, $name, $type);
    }
}
