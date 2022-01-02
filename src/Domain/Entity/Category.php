<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\CategoryType;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Category
{
    private Id $id;
    private string $name;
    private int $position;
    private CategoryType $type;
    private User $user;
    private Icon $icon;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $id,
        User $user,
        string $name,
        CategoryType $type,
        Icon $icon,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->position = 0;
        $this->type = $type;
        $this->icon = $icon;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getType(): CategoryType
    {
        return $this->type;
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getIcon(): Icon
    {
        return $this->icon;
    }
}
