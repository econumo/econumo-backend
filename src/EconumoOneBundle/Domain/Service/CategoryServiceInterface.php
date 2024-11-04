<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

interface CategoryServiceInterface
{
    public function createCategory(Id $userId, CategoryName $name, CategoryType $type, Icon $icon): Category;

    public function createCategoryForAccount(Id $userId, Id $accountId, CategoryName $name, CategoryType $type, Icon $icon): Category;

    public function deleteCategory(Id $categoryId): void;

    public function replaceCategory(Id $categoryId, Id $newCategoryId): void;

    /**
     * @param Id $userId
     * @param PositionDto[] $changes
     * @return void
     */
    public function orderCategories(Id $userId, array $changes): void;

    public function update(Id $categoryId, CategoryName $name, Icon $icon): void;

    public function archive(Id $categoryId): void;

    public function unarchive(Id $categoryId): void;
}
