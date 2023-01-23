<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Events\RemindPasswordRequestedEvent;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;


class PasswordUserRequest
{
    use EventTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    private DateTimeInterface $expiredAt;

    public function __construct(
        private Id $id,
        private User $user,
        private ?string $code,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->expiredAt = $this->createdAt->modify('+12 hours');
        $this->registerEvent(new RemindPasswordRequestedEvent($this->id));
    }

    public function codeUsed(): void
    {
        $this->code = null;
        $this->updatedAt = new DateTime();
    }
}
