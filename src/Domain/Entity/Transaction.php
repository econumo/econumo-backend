<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Transaction
{
    private Id $id;
    private User $user;
    private TransactionType $type;
    private Account $account;
    private ?Account $accountRecipient;
    private string $amount;
    private ?string $amountRecipient;
    private ?Category $category;
    private string $description;
    private ?Payee $payee;
    private ?Tag $tag;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;
    private DateTimeInterface $spentAt;

    public function __construct(
        Id $id,
        User $user,
        TransactionType $type,
        Account $account,
        ?Category $category,
        float $amount,
        DateTimeInterface $transactionDate,
        DateTimeInterface $createdAt,
        ?Account $accountRecipient,
        ?float $amountRecipient,
        string $description,
        ?Payee $payee,
        ?Tag $tag
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->type = $type;
        $this->account = $account;
        $this->category = $category;
        $this->amount = (string)$amount;
        $this->accountRecipient = $accountRecipient;
        $this->amountRecipient = $amountRecipient === null ? null : (string)$amountRecipient;
        $this->description = $description;
        $this->payee = $payee;
        $this->tag = $tag;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->spentAt = DateTime::createFromFormat('Y-m-d H:i:s', $transactionDate->format('Y-m-d H:i:s'));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getUserId(): Id
    {
        return $this->user->getId();
    }

    public function getType(): TransactionType
    {
        return $this->type;
    }

    public function getAccountId(): Id
    {
        return $this->account->getId();
    }

    public function getAccountRecipientId(): ?Id
    {
        return $this->accountRecipient !== null ? $this->accountRecipient->getId() : null;
    }

    public function getAmount(): float
    {
        return (float)$this->amount;
    }

    public function getAmountRecipient(): ?float
    {
        return $this->amountRecipient === null ? null : (float)$this->amountRecipient;
    }

    public function getCategoryId(): ?Id
    {
        return $this->category !== null ? $this->category->getId() : null;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPayeeId(): ?Id
    {
        return $this->payee !== null ? $this->payee->getId() : null;
    }

    public function getTagId(): ?Id
    {
        return $this->tag !== null ? $this->tag->getId() : null;
    }

    public function getSpentAt(): DateTimeInterface
    {
        return $this->spentAt;
    }
}
