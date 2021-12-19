<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\Id;

final class UserRegisteredEvent
{
    private Id $userId;

    public function __construct(Id $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }
}
