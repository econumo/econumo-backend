<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;


class RequestId
{
    private Id $requestId;
    private ?Id $internalId = null;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(Id $id, DateTimeInterface $createdAt)
    {
        $this->requestId = $id;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function updateInternal(Id $id): void
    {
        $this->internalId = $id;
        $this->updatedAt = new \DateTime();
    }
}
