<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountRole;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class AccountAccess
{
    private Id $accountId;
    private Id $userId;
    private AccountRole $role;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $accountId,
        Id $userId,
        AccountRole $role,
        \DateTimeInterface $createdAt
    ) {
        $this->accountId = $accountId;
        $this->userId = $userId;
        $this->role = $role;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getAccountId(): Id
    {
        return $this->accountId;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function getRole(): AccountRole
    {
        return $this->role;
    }
}
