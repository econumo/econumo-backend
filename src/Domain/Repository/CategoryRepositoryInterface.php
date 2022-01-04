<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Category;
use App\Domain\Entity\ValueObject\Id;

interface CategoryRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $userId
     * @return Category[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @param Id $userId
     * @return Category[]
     */
    public function findByOwnerId(Id $userId): array;

    public function get(Id $id): Category;

    public function save(Category ...$categories): void;

    public function getReference(Id $id): Category;

    public function delete(Category $category): void;
}
