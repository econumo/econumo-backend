<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;

class PlanDataExchangeDto
{
    public Id $currencyId;

    public float $budget;

    public float $amount;
}
