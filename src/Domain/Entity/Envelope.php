<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\EnvelopeName;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\EnvelopeType;
use App\Domain\Events\PlanEnvelopeCreatedEvent;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Envelope
{
    use EventTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private User $user,
        private Id $planId,
        private Id $currencyId,
        private ?Id $folderId,
        private EnvelopeType $type,
        private int $position,
        private ?EnvelopeName $name,
        private ?Icon $icon,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->registerEvent(new PlanEnvelopeCreatedEvent($id, $planId, $user->getId(), $currencyId, $folderId, $type, $position, $name, $icon, $createdAt));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): ?EnvelopeName
    {
        return $this->name;
    }

    public function getOwnerUserId(): Id
    {
        return $this->user->getId();
    }

    public function getOwnerUser(): User
    {
        return $this->user;
    }

    public function updateName(EnvelopeName $name): void
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
