<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Folder;
use App\Domain\Entity\ValueObject\FolderName;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

interface FolderServiceInterface
{
    public function create(Id $userId, FolderName $name): Folder;

    public function update(Id $folderId, FolderName $name): void;

    public function delete(Id $folderId): void;

    public function replace(Id $folderId, Id $replaceFolderId): void;

    public function orderFolders(Id $userId, PositionDto ...$changes): void;

    public function hide(Id $folderId): void;

    public function show(Id $folderId): void;

    /**
     * @param Id $userId
     * @param DateTimeInterface $lastUpdate
     * @return Folder[]
     */
    public function getChanged(Id $userId, DateTimeInterface $lastUpdate): array;
}
