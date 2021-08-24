<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Id;

interface CategoryServiceInterface
{
    public function createCategory(Id $userId, Id $id, string $name, CategoryType $type): Category;
}
