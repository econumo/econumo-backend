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
    private bool $isArchived;
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
        $this->isArchived = false;
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

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function updateName(string $name): void
    {
        if ($this->name !== $name) {
            $this->name = $name;
            $this->updated();
        }
    }

    public function updateIcon(Icon $icon): void
    {
        if (!$this->icon->isEqual($icon)) {
            $this->icon = $icon;
            $this->updated();
        }
    }

    public function updatePosition(int $position): void
    {
        if ($this->position !== $position) {
            $this->position = $position;
            $this->updated();
        }
    }

    public function archive(): void
    {
        if (!$this->isArchived) {
            $this->isArchived = true;
            $this->updated();
        }
    }

    public function unarchive(): void
    {
        if ($this->isArchived) {
            $this->isArchived = false;
            $this->updated();
        }
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    private function updated()
    {
        $this->updatedAt = new DateTime();
    }
}
