<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Tag;
use App\Domain\Entity\ValueObject\Id;

interface TagRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Tag[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @return Tag[]
     */
    public function findByOwnerId(Id $userId): array;

    /**
     * @return Tag[]
     */
    public function findByOwnersIds(array $userIds): array;

    public function get(Id $id): Tag;

    /**
     * @param Tag[] $tags
     */
    public function save(array $tags): void;

    public function getReference(Id $id): Tag;

    public function delete(Tag $tag): void;

    /**
     * @param Id[] $ids
     * @return Tag[]
     */
    public function getByIds(array $ids): array;
}
