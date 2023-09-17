<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Plan
{
    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private readonly Id $id,
        private readonly User $user,
        private PlanName $name,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): PlanName
    {
        return $this->name;
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function updateName(PlanName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
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
