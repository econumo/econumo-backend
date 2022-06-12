<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\CategoryName;
use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;

interface CategoryFactoryInterface
{
    public function create(Id $userId, CategoryName $name, CategoryType $type, Icon $icon): Category;
}
