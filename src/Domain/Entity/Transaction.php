<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Doctrine\Repository\TransactionRepository")
 * @ORM\Table(name="`transactions`")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\CustomIdGenerator("NONE")
     * @ORM\Column(type="uuid")
     */
    private Id $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $userId;

    /**
     * @ORM\Column(type="transaction_type")
     */
    private TransactionType $type;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $accountId;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    private ?Id $accountRecipientId;

    /**
     * @ORM\Column(type="decimal", precision=19, scale=2)
     */
    private string $amount;

    /**
     * @ORM\Column(type="decimal", precision=19, scale=2, nullable=true)
     */
    private ?string $amountRecipient;

    /**
     * @ORM\Column(type="uuid")
     */
    private Id $categoryId;

    /**
     * @ORM\Column(type="string")
     */
    private string $description;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    private ?Id $payeeId;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     */
    private ?Id $tagId;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $spentAt;

    public function __construct(
        Id $id,
        Id $userId,
        TransactionType $type,
        Id $accountId,
        Id $categoryId,
        float $amount,
        DateTimeInterface $createdAt,
        ?Id $accountRecipientId = null,
        ?float $amountRecipient,
        string $description = '',
        ?Id $payeeId = null,
        ?Id $tagId = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->type = $type;
        $this->accountId = $accountId;
        $this->categoryId = $categoryId;
        $this->amount = (string)$amount;
        $this->accountRecipientId = $accountRecipientId;
        $this->amountRecipient = (string)$amountRecipient;
        $this->description = $description;
        $this->payeeId = $payeeId;
        $this->tagId = $tagId;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
        $this->spentAt = DateTime::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
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

    public function getCategoryId(): Id
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
