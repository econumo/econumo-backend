<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TagName;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;


class Tag
{
    private int $position = 0;

    private bool $isArchived = false;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private User $user,
        private TagName $name,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): TagName
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function updateName(TagName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
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

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
