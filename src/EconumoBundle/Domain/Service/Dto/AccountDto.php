<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

class AccountDto
{
    public Id $userId;

    public float $balance;

    public string $name;

    public Id $currencyId;

    public string $icon;

    public ?Id $folderId = null;
}
