<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\Id;

final readonly class FolderCreatedEvent
{
    public function __construct(private Id $userId, private Id $folderId)
    {
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
