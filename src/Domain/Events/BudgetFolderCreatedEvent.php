<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\Id;

final readonly class BudgetFolderCreatedEvent
{
    public function __construct(private Id $folderId)
    {
    }

    public function getFolderId(): Id
    {
        return $this->folderId;
    }
}
