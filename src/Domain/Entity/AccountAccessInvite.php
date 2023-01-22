<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class AccountAccessInvite
{
    private Account $account;

    private User $recipient;

    private User $owner;

    private AccountUserRole $role;

    private string $code;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        Account $account,
        User $recipient,
        User $owner,
        AccountUserRole $role,
        string $code,
        \DateTimeInterface $createdAt
    ) {
        $this->account = $account;
        $this->recipient = $recipient;
        $this->owner = $owner;
        $this->role = $role;
        $this->code = $code;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getRecipientId(): Id
    {
        return $this->recipient->getId();
    }

    public function getAccountId(): Id
    {
        return $this->account->getId();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRole(): AccountUserRole
    {
        return $this->role;
    }
}
