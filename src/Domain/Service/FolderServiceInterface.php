<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;

interface FolderServiceInterface
{
    public function create(Id $userId, FolderName $name): Folder;

    public function update(Id $folderId, FolderName $name): void;

    public function delete(Id $folderId): void;
}
