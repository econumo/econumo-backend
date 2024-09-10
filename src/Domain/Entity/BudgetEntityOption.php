<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\BudgetEntityType;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class BudgetEntityOption
{
    private DateTimeInterface $finishedAt;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $entityId,
        private BudgetEntityType $entityType,
        private Budget $budget,
        private ?Currency $currency,
        private int $position,
        ?DateTimeInterface $finishedAt,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->finishedAt = DateTime::createFromFormat('Y-m-d H:i:s', $finishedAt->format('Y-m-01 00:00:00'));
    }

    public function getBudget(): Budget
    {
        return $this->budget;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function getEntityId(): Id
    {
        return $this->entityId;
    }

    public function getEntityType(): BudgetEntityType
    {
        return $this->entityType;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function updatePosition(int $position): void
    {
        if ($this->position !== $position) {
            $this->position = $position;
            $this->updated();
        }
    }

    public function getFinishedAt(): ?DateTimeInterface
    {
        return $this->finishedAt;
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}