<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;

class PlanDataBalanceDto
{
    public Id $currencyId;

    public ?float $startBalance;

    public ?float $endBalance = null;

    public ?float $currentBalance = null;
}
