<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PositionDto;

interface FolderServiceInterface
{
    public function create(Id $userId, FolderName $name): Folder;

    public function update(Id $folderId, FolderName $name): void;

    public function delete(Id $folderId): void;

    public function replace(Id $folderId, Id $replaceFolderId): void;

    public function orderFolders(Id $userId, PositionDto ...$changes): void;
}
