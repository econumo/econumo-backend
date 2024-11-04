<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Events;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

final readonly class RemindPasswordRequestedEvent
{
    public function __construct(private Id $id)
    {
    }

    public function getId(): Id
    {
        return $this->id;
    }
}
