<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity;

use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Entity\BudgetFolder;
use App\EconumoBundle\Domain\Entity\ValueObject\BudgetEntityType;
use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class BudgetEntityOption
{
    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $entityId,
        private BudgetEntityType $entityType,
        private Budget $budget,
        private ?Currency $currency,
        private ?BudgetFolder $folder,
        private int $position,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getBudget(): Budget
    {
        return $this->budget;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function getFolder(): ?BudgetFolder
    {
        return $this->folder;
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

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}