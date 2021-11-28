<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\Id;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Account
{
    private Id $id;
    private string $name;
    private int $position;
    private Currency $currency;
    private string $balance;
    private AccountType $type;
    private string $icon;
    private User $user;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        Id $id,
        User $user,
        string $name,
        Currency $currency,
        float $balance,
        AccountType $type,
        string $icon,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->currency = $currency;
        $this->balance = (string)$balance;
        $this->type = $type;
        $this->icon = $icon;
        $this->position = 1000;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getCurrencyId(): Id
    {
        return $this->currency->getId();
    }

    public function applyTransaction(Transaction $transaction): void
    {
        if ($transaction->getType()->isExpense()) {
            $this->balance = (string)((float)$this->balance - $transaction->getAmount());
        } elseif ($transaction->getType()->isIncome()) {
            $this->balance = (string)((float)$this->balance + $transaction->getAmount());
        } elseif ($transaction->getType()->isTransfer()) {
            if ($transaction->getAccountId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance - $transaction->getAmount());
            } elseif ($transaction->getAccountRecipientId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance + $transaction->getAmount());
            }
        }
    }

    public function rollbackTransaction(Transaction $transaction): void
    {
        if ($transaction->getType()->isExpense()) {
            $this->balance = (string)((float)$this->balance + $transaction->getAmount());
        } elseif ($transaction->getType()->isIncome()) {
            $this->balance = (string)((float)$this->balance - $transaction->getAmount());
        } elseif ($transaction->getType()->isTransfer()) {
            if ($transaction->getAccountId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance + $transaction->getAmount());
            } elseif ($transaction->getAccountRecipientId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance - $transaction->getAmount());
            }
        }
    }

    public function getBalance(): float
    {
        return (float)$this->balance;
    }

    public function getType(): AccountType
    {
        return $this->type;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updateIcon(string $icon): void
    {
        $this->icon = $icon;
    }
}
