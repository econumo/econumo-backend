<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

class PlanDataCurrencyRateDto
{
    public Id $currencyId;

    public Id $baseCurrencyId;

    public float $rate;

    public DateTimeInterface $date;
}
