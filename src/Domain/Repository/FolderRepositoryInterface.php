<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\Id;

interface FolderRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $userId
     * @return Folder[]
     */
    public function getByUserId(Id $userId): array;

    public function get(Id $id): Folder;

    public function save(Folder ...$items): void;

    public function delete(Folder $folder): void;

    public function isUserHasMoreThanOneFolder(Id $userId): bool;
}
