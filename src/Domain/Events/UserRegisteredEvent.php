<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\Id;

final readonly class UserRegisteredEvent
{
    public function __construct(private Id $userId)
    {
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }
}
