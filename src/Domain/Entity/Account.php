<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\AccountName;
use App\Domain\Entity\ValueObject\AccountType;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Icon;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Traits\EntityTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Account
{
    use EntityTrait;

    private string $balance;

    private bool $isDeleted = false;

    private DateTimeImmutable $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        private Id $id,
        private User $user,
        private AccountName $name,
        private Currency $currency,
        private AccountType $type,
        private Icon $icon,
        DateTimeInterface $createdAt
    ) {
        $this->balance = '0';
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

    public function getName(): AccountName
    {
        return $this->name;
    }

    public function isExcludedFromBudgeting(): bool
    {
        return false;
    }

    public function getCurrencyId(): Id
    {
        return $this->currency->getId();
    }

    public function getCurrencyCode(): CurrencyCode
    {
        return $this->currency->getCode();
    }

    public function applyTransaction(Transaction $transaction): void
    {
        if ($transaction->getAccountCurrency()->isEqual($this->getCurrencyCode())) {
            $amount = $transaction->getAmount();
        } else {
            $amount = $transaction->getAmountRecipient();
        }

        if ($transaction->getType()->isExpense()) {
            $this->balance = (string)((float)$this->balance - $amount);
        } elseif ($transaction->getType()->isIncome()) {
            $this->balance = (string)((float)$this->balance + $amount);
        } elseif ($transaction->getType()->isTransfer()) {
            if ($transaction->getAccountId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance - $amount);
            } elseif ($transaction->getAccountRecipientId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance + $amount);
            }
        }
    }

    public function rollbackTransaction(Transaction $transaction): void
    {
        if ($transaction->getAccountCurrency()->isEqual($this->getCurrencyCode())) {
            $amount = $transaction->getAmount();
        } else {
            $amount = $transaction->getAmountRecipient();
        }

        if ($transaction->getType()->isExpense()) {
            $this->balance = (string)((float)$this->balance + $amount);
        } elseif ($transaction->getType()->isIncome()) {
            $this->balance = (string)((float)$this->balance - $amount);
        } elseif ($transaction->getType()->isTransfer()) {
            if ($transaction->getAccountId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance + $amount);
            } elseif ($transaction->getAccountRecipientId()->isEqual($this->id)) {
                $this->balance = (string)((float)$this->balance - $amount);
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

    public function getIcon(): Icon
    {
        return $this->icon;
    }

    public function updateName(AccountName $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->name = $name;
            $this->updated();
        }
    }

    public function updateIcon(Icon $icon): void
    {
        if (!$this->icon->isEqual($icon)) {
            $this->icon = $icon;
            $this->updated();
        }
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function delete(): void
    {
        if (!$this->isDeleted) {
            $this->isDeleted = true;
            $this->updated();
        }
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    private function updated(): void
    {
        $this->updatedAt = new DateTime();
    }
}
