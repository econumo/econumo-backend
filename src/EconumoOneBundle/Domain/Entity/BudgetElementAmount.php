<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Entity;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetElementType;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class BudgetElementAmount
{
    private DateTimeImmutable $period;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Budget $budget,
        private Id $elementId,
        private BudgetElementType $elementType,
        private float $amount,
        DateTimeInterface $period,
        DateTimeInterface $createdAt
    ) {
        $this->period = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getBudget(): Budget
    {
        return $this->budget;
    }

    public function getElementId(): Id
    {
        return $this->elementId;
    }

    public function getElementType(): BudgetElementType
    {
        return $this->elementType;
    }

    public function getAmount(): float
    {
        return round($this->amount, 2);
    }

    public function updateAmount(float $amount): void
    {
        if ($this->amount !== $amount) {
            $this->amount = round($amount, 2);
            $this->updated();
        }
    }

    public function getPeriod(): DateTimeImmutable
    {
        return $this->period;
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}