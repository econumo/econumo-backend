<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;

interface CategoryServiceInterface
{
    public function createCategory(Id $userId, string $name, CategoryType $type, Icon $icon): Category;

    public function createCategoryForAccount(Id $userId, Id $accountId, string $name, CategoryType $type, Icon $icon): Category;
}
