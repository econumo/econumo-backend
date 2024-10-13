<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CategoryType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface CategoryFactoryInterface
{
    public function create(Id $userId, CategoryName $name, CategoryType $type, Icon $icon): Category;
}
