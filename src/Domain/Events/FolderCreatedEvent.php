<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\Id;

final class FolderCreatedEvent
{
    private Id $userId;
    private Id $folderId;

    public function __construct(Id $userId, Id $folderId)
    {
        $this->userId = $userId;
        $this->folderId = $folderId;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getFolderId(): Id
    {
        return $this->folderId;
    }
}
