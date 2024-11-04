<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Folder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\FolderName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

interface FolderServiceInterface
{
    public function create(Id $userId, FolderName $name): Folder;

    public function update(Id $folderId, FolderName $name): void;

    public function delete(Id $folderId): void;

    public function replace(Id $folderId, Id $replaceFolderId): void;

    /**
     * @param Id $userId
     * @param PositionDto[] $changes
     * @return void
     */
    public function orderFolders(Id $userId, array $changes): void;

    public function hide(Id $folderId): void;

    public function show(Id $folderId): void;
}
