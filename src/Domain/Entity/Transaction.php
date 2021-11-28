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
    private Id $userId;
    private TransactionType $type;
    private Id $accountId;
    private ?Id $accountRecipientId;
    private string $amount;
    private ?string $amountRecipient;
    private ?Id $categoryId;
    private string $description;
    private ?Id $payeeId;
    private ?Id $tagId;
    private DateTimeImmutable $createdAt;
    private DateTimeInterface $updatedAt;
    private DateTimeInterface $spentAt;

    public function __construct(
        Id $id,
        Id $userId,
        TransactionType $type,
        Id $accountId,
        ?Id $categoryId,
        float $amount,
        DateTimeInterface $transactionDate,
        DateTimeInterface $createdAt,
        ?Id $accountRecipientId,
        ?float $amountRecipient,
        string $description,
        ?Id $payeeId,
        ?Id $tagId
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->type = $type;
        $this->accountId = $accountId;
        $this->categoryId = $categoryId;
        $this->amount = (string)$amount;
        $this->accountRecipientId = $accountRecipientId;
        $this->amountRecipient = $amountRecipient === null ? null : (string)$amountRecipient;
        $this->description = $description;
        $this->payeeId = $payeeId;
        $this->tagId = $tagId;
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
        return $this->userId;
    }

    public function getType(): TransactionType
    {
        return $this->type;
    }

    public function getAccountId(): Id
    {
        return $this->accountId;
    }

    public function getAccountRecipientId(): ?Id
    {
        return $this->accountRecipientId;
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
        return $this->categoryId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPayeeId(): ?Id
    {
        return $this->payeeId;
    }

    public function getTagId(): ?Id
    {
        return $this->tagId;
    }

    public function getSpentAt(): DateTimeInterface
    {
        return $this->spentAt;
    }
}
