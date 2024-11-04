<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface CategoryRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Category[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @return Category[]
     */
    public function findByOwnerId(Id $userId): array;

    public function get(Id $id): Category;

    /**
     * @param Category[] $categories
     */
    public function save(array $categories): void;

    public function getReference(Id $id): Category;

    public function delete(Category $category): void;

    /**
     * @param Id[] $ids
     * @return Category[]
     */
    public function getByIds(array $ids): array;

    /**
     * @param Id[] $userIds
     * @return Category[]
     */
    public function findByOwnersIds(array $userIds, bool $onlyActive = null): array;
}
