<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\Account;
use App\EconumoOneBundle\Domain\Entity\Category;
use App\EconumoOneBundle\Domain\Entity\Payee;
use App\EconumoOneBundle\Domain\Entity\Tag;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\TransactionType;
use DateTimeInterface;

class TransactionDto
{
    public TransactionType $type;

    public Id $userId;

    public float $amount;

    public ?float $amountRecipient = null;

    public Id $accountId;

    public Account $account;

    public ?Id $accountRecipientId = null;

    public ?Account $accountRecipient = null;

    public ?Id $categoryId = null;

    public ?Category $category = null;

    public DateTimeInterface $date;

    public string $description;

    public ?Id $payeeId = null;

    public ?Payee $payee = null;

    public ?Id $tagId = null;

    public ?Tag $tag = null;
}
