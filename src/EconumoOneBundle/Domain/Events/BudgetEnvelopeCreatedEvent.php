<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Events;

use App\EconumoOneBundle\Domain\Entity\ValueObject\BudgetEnvelopeName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Icon;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

final readonly class BudgetEnvelopeCreatedEvent
{
    public function __construct(
        private Id $id,
        private Id $budgetId,
        private BudgetEnvelopeName $name,
        private Icon $icon,
        private DateTimeInterface $createdAt
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): ?BudgetEnvelopeName
    {
        return $this->name;
    }

    public function getIcon(): Icon
    {
        return $this->icon;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
