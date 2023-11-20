<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\UserPasswordRequestCode;
use App\Domain\Events\RemindPasswordRequestedEvent;
use App\Domain\Traits\EntityTrait;
use App\Domain\Traits\EventTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;


class UserPasswordRequest
{
    use EntityTrait;
    use EventTrait;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    private DateTimeInterface $expiredAt;

    public function __construct(
        private Id $id,
        private User $user,
        private UserPasswordRequestCode $code,
        DateTimeInterface $createdAt
    ) {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->expiredAt = $this->createdAt->modify('+10 minutes');
        $this->registerEvent(new RemindPasswordRequestedEvent($this->id));
    }

    public function getCode(): UserPasswordRequestCode
    {
        return $this->code;
    }

    public function isExpired(): bool
    {
        return $this->expiredAt < new DateTimeImmutable();
    }
}
