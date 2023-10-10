<?php

declare(strict_types=1);

namespace App\Domain\Events;

use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\EnvelopeType;
use DateTimeInterface;

final readonly class PlanEnvelopeCreatedEvent
{
    public function __construct(
        private Id $id,
        private Id $planId,
        private Id $userId,
        private Id $currencyId,
        private Id $folderId,
        private EnvelopeType $type,
        private int $position,
        private ?EnvelopeName $name,
        private ?Icon $icon,
        private DateTimeInterface $createdAt
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getPlanId(): Id
    {
        return $this->planId;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getCurrencyId(): Id
    {
        return $this->currencyId;
    }

    public function getFolderId(): Id
    {
        return $this->folderId;
    }

    public function getType(): EnvelopeType
    {
        return $this->type;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getName(): ?EnvelopeName
    {
        return $this->name;
    }

    public function getIcon(): ?Icon
    {
        return $this->icon;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
