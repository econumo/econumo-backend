<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\TransactionType;
use DateTimeInterface;

class TransactionDto
{
    public TransactionType $type;

    public Id $userId;

    public float $amount;

    public ?float $amountRecipient = null;

    public Id $accountId;

    public ?Id $accountRecipientId = null;

    public ?Id $categoryId = null;

    public DateTimeInterface $date;

    public string $description;

    public ?Id $payeeId = null;

    public ?Id $tagId = null;
}
