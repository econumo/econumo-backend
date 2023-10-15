<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Events\PlanEnvelopeCreatedEvent;
use App\Domain\Traits\EntityTrait;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EnvelopeBudget
{
    use EntityTrait;

    private DateTimeInterface $period;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private Envelope $envelope,
        private float $amount,
        DateTimeInterface $period,
        DateTimeInterface $createdAt
    ) {
        $this->period = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $period->format('Y-m-01 00:00:00'));
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPeriod(): DateTimeInterface
    {
        return $this->period;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getEnvelope(): Envelope
    {
        return $this->envelope;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function updateAmount(float $amount): void
    {
        if ($this->amount !== $amount) {
            $this->amount = $amount;
            $this->updated();
        }
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
