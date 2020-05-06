<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\Id;

interface CategoryRepositoryInterface
{
    /**
     * @param Id $id
     * @return Category[]
     */
    public function findByUserId(Id $id): array;
}
