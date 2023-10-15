<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Events\PlanFolderCreatedEvent;
use App\Domain\Traits\EntityTrait;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class PlanFolder
{
    use EntityTrait;
    use EventTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private Plan $plan,
        private PlanFolderName $name,
        private int $position,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->registerEvent(new PlanFolderCreatedEvent($id));
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): PlanFolderName
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function updateName(PlanFolderName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
            $this->updated();
        }
    }

    public function updatePosition(int $position): void
    {
        if ($this->position !== $position) {
            $this->position = $position;
            $this->updated();
        }
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
