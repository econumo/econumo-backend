<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountUserRole;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class AccountAccess
{
    private Id $id;
    private Account $account;
    private User $user;
    private AccountUserRole $role;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        Account $account,
        User $user,
        AccountUserRole $role,
        \DateTimeInterface $createdAt
    ) {
        $this->account = $account;
        $this->user = $user;
        $this->role = $role;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getAccountId(): Id
    {
        return $this->account->getId();
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getRole(): AccountUserRole
    {
        return $this->role;
    }

    public function updateRole(AccountUserRole $role): void
    {
        if (!$this->role->isEqual($role)) {
            $this->role = $role;
            $this->updated();
        }
    }

    private function updated()
    {
        $this->updatedAt = new DateTime();
    }
}
