<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;

interface FolderServiceInterface
{
    public function delete(Id $userId, Id $folderId): void;
}
