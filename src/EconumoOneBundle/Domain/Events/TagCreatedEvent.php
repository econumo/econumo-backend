<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Events;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

final readonly class TagCreatedEvent
{
    public function __construct(private Id $userId, private Id $tagId)
    {
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getTagId(): Id
    {
        return $this->tagId;
    }
}
