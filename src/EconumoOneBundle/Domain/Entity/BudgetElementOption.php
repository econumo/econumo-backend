<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Entity;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class BudgetElementOption
{
    public const POSITION_UNSET = -1;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $elementId,
        private BudgetElementType $elementType,
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

    public function changeFolder(?BudgetFolder $budgetFolder): void
    {
        $this->folder = $budgetFolder;
    }

    public function getElementId(): Id
    {
        return $this->elementId;
    }

    public function getElementType(): BudgetElementType
    {
        return $this->elementType;
    }

    public function isPositionUnset(): bool
    {
        return self::POSITION_UNSET === $this->position;
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

    public function unsetPosition(): void
    {
        if ($this->position !== self::POSITION_UNSET) {
            $this->position = self::POSITION_UNSET;
            $this->updated();
        }
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}