<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\Id;

final class RemindPasswordRequestedEvent
{
    private Id $id;

    public function __construct(Id $remindPasswordRequest)
    {
        $this->id = $remindPasswordRequest;
    }

    public function getId(): Id
    {
        return $this->id;
    }
}
