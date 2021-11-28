<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class AccountAccessInvite
{
    private Id $accountId;
    private Id $recipientId;
    private Id $ownerId;
    private AccountRole $role;
    private string $code;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $accountId,
        Id $recipientId,
        Id $ownerId,
        AccountRole $role,
        string $code,
        \DateTimeInterface $createdAt
    ) {
        $this->accountId = $accountId;
        $this->recipientId = $recipientId;
        $this->ownerId = $ownerId;
        $this->role = $role;
        $this->code = $code;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getRecipientId(): Id
    {
        return $this->recipientId;
    }

    public function getAccountId(): Id
    {
        return $this->accountId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRole(): AccountRole
    {
        return $this->role;
    }
}
