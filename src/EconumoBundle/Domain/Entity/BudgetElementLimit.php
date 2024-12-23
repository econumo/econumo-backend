<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class BudgetElementLimit
{
    private string $amount;

    private DateTimeImmutable $period;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private BudgetElement $element,
        float $amount,
        DateTimeInterface $period,
        DateTimeInterface $createdAt
    ) {
        $this->amount = (string)round($amount, 2);
        $this->period = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getElementId(): Id
    {
        return $this->element->getId();
    }

    public function getElement(): BudgetElement
    {
        return $this->element;
    }

    public function getAmount(): float
    {
        return round((float)$this->amount, 2);
    }

    public function updateAmount(float $amount): void
    {
        if (round((float)$this->amount, 2) !== round($amount, 2)) {
            $this->amount = (string)round($amount, 2);
            $this->updated();
        }
    }

    public function getPeriod(): DateTimeImmutable
    {
        return $this->period;
    }

    public function getCreatedAt(): DateTimeImmutable
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