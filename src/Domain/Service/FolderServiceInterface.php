<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;

interface FolderServiceInterface
{
    public function create(Id $userId, FolderName $name): Folder;

    public function delete(Id $userId, Id $folderId): void;
}
