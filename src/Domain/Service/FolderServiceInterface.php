<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;

interface FolderServiceInterface
{
    public function userHaveTheOnlyFolder(Id $userId): bool;

    public function delete(Id $folderId): void;
}
