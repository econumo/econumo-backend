<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\ConnectionCode;
use App\Domain\Entity\ValueObject\Id;
use DateTime;

class ConnectionInvite
{
    private const INVITE_LIFETIME = '+5 minutes';
    private User $user;
    private ?ConnectionCode $code;
    private ?DateTime $expiredAt;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->code = null;
        $this->expiredAt = null;
    }

    public function generateNewCode(): void
    {
        $this->code = ConnectionCode::generate();
        $this->expiredAt = new DateTime(self::INVITE_LIFETIME);
    }

    public function clearCode(): void
    {
        $this->code = null;
        $this->expiredAt = null;
    }

    public function getCode(): ?ConnectionCode
    {
        return $this->code;
    }

    public function getExpiredAt(): ?DateTime
    {
        return $this->expiredAt;
    }

    public function isExpired(): bool
    {
        return $this->expiredAt < new DateTime();
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }
}
