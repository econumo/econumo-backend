<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PositionDto;

interface CategoryServiceInterface
{
    public function createCategory(Id $userId, string $name, CategoryType $type, Icon $icon): Category;

    public function createCategoryForAccount(Id $userId, Id $accountId, string $name, CategoryType $type, Icon $icon): Category;

    public function deleteCategory(Id $categoryId): void;

    public function replaceCategory(Id $categoryId, Id $newCategoryId): void;

    public function orderCategories(Id $userId, PositionDto ...$changes): void;

    public function update(Id $categoryId, bool $isArchived, string $name, Icon $icon): void;

    public function archive(Id $categoryId): void;

    public function unarchive(Id $categoryId): void;
}
