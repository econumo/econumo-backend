<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Events;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

final readonly class CategoryCreatedEvent
{
    public function __construct(private Id $userId, private Id $categoryId)
    {
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getCategoryId(): Id
    {
        return $this->categoryId;
    }
}
