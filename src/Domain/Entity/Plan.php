<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Traits\EntityTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Plan
{
    use EntityTrait;

    private DateTimeInterface $startDate;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private User $user,
        private PlanName $name,
        DateTimeInterface $startDate,
        DateTimeInterface $createdAt
    ) {
        $this->startDate = DateTime::createFromFormat('Y-m-d H:i:s', $startDate->format('Y-m-d H:i:s'));
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

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function getOwner(): User
    {
        return $this->user;
    }

    public function getOwnerUserId(): Id
    {
        return $this->user->getId();
    }

    public function updateName(PlanName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
            $this->updated();
        }
    }

    public function updateStartDate(DateTimeInterface $dateTime): void
    {
        if ($this->startDate->getTimestamp() !== $dateTime->getTimestamp()) {
            $this->startDate = $dateTime;
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
