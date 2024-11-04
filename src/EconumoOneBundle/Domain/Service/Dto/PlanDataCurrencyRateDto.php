<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

class PlanDataCurrencyRateDto
{
    public Id $currencyId;

    public Id $baseCurrencyId;

    public float $rate;

    public DateTimeInterface $date;
}
