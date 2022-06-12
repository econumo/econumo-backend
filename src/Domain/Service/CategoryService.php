<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryName;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\CategoryAlreadyExistsException;
use App\Domain\Exception\ReplaceCategoryException;
use App\Domain\Factory\CategoryFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Service\Dto\PositionDto;

class CategoryService implements CategoryServiceInterface
{
    private CategoryFactoryInterface $categoryFactory;
    private CategoryRepositoryInterface $categoryRepository;
    private AccountRepositoryInterface $accountRepository;
    private AntiCorruptionServiceInterface $antiCorruptionService;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        CategoryFactoryInterface $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        AccountRepositoryInterface $accountRepository,
        AntiCorruptionServiceInterface $antiCorruptionService,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->accountRepository = $accountRepository;
        $this->antiCorruptionService = $antiCorruptionService;
        $this->transactionRepository = $transactionRepository;
    }

    public function createCategory(Id $userId, CategoryName $name, CategoryType $type, Icon $icon): Category
    {
        $categories = $this->categoryRepository->findByOwnerId($userId);
        foreach ($categories as $category) {
            if ($category->getName()->isEqual($name)) {
                throw new CategoryAlreadyExistsException();
            }
        }

        $category = $this->categoryFactory->create($userId, $name, $type, $icon);
        $category->updatePosition(count($categories));
        $this->categoryRepository->save($category);

        return $category;
    }

    public function createCategoryForAccount(
        Id $userId,
        Id $accountId,
        CategoryName $name,
        CategoryType $type,
        Icon $icon
    ): Category {
        $account = $this->accountRepository->get($accountId);
        if ($userId->isEqual($account->getUserId())) {
            return $this->createCategory($userId, $name, $type, $icon);
        }

        return $this->createCategory($account->getUserId(), $name, $type, $icon);
    }

    public function deleteCategory(Id $categoryId): void
    {
        $category = $this->categoryRepository->get($categoryId);
        $this->categoryRepository->delete($category);
    }

    public function replaceCategory(Id $categoryId, Id $newCategoryId): void
    {
        $category = $this->categoryRepository->get($categoryId);
        $newCategory = $this->categoryRepository->get($newCategoryId);
        if (!$category->getUserId()->isEqual($newCategory->getUserId())) {
            throw new ReplaceCategoryException();
        }
        if (!$category->getType()->isEqual($newCategory->getType())) {
            throw new ReplaceCategoryException();
        }

        $this->antiCorruptionService->beginTransaction();
        try {
            $this->transactionRepository->replaceCategory($categoryId, $newCategoryId);
            $this->categoryRepository->delete($category);
            $this->antiCorruptionService->commit();
        } catch (\Throwable $exception) {
            $this->antiCorruptionService->rollback();
            throw $exception;
        }
    }

    public function orderCategories(Id $userId, PositionDto ...$changes): void
    {
        $categories = $this->categoryRepository->findByOwnerId($userId);
        $changed = [];
        foreach ($categories as $category) {
            foreach ($changes as $change) {
                if ($category->getId()->isEqual($change->getId())) {
                    $category->updatePosition($change->position);
                    $changed[] = $category;
                    break;
                }
            }
        }

        if (!count($changed)) {
            return;
        }
        $this->categoryRepository->save(...$changed);
    }

    public function update(Id $categoryId, CategoryName $name, Icon $icon): void
    {
        $category = $this->categoryRepository->get($categoryId);
        $userCategories = $this->categoryRepository->findByOwnerId($category->getUserId());
        foreach ($userCategories as $userCategory) {
            if ($userCategory->getName()->isEqual($name) && !$userCategory->getId()->isEqual($categoryId)) {
                throw new CategoryAlreadyExistsException();
            }
        }

        $category->updateName($name);
        $category->updateIcon($icon);
        $this->categoryRepository->save($category);
    }

    public function archive(Id $categoryId): void
    {
        $category = $this->categoryRepository->get($categoryId);
        $category->archive();
        $this->categoryRepository->save($category);
    }

    public function unarchive(Id $categoryId): void
    {
        $category = $this->categoryRepository->get($categoryId);
        $category->unarchive();
        $this->categoryRepository->save($category);
    }
}
