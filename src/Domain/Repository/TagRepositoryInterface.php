<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;

interface TagRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $userId
     * @return Tag[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @param Id $userId
     * @return Tag[]
     */
    public function findByOwnerId(Id $userId): array;

    public function get(Id $id): Tag;

    /**
     * @param Tag[] $tags
     * @return void
     */
    public function save(array $tags): void;

    public function getReference(Id $id): Tag;

    public function delete(Tag $tag): void;
}
