<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

class FullCurrencyRateDto
{
    public Id $currencyId;

    public CurrencyCode $currencyCode;

    public Id $baseCurrencyId;

    public CurrencyCode $baseCurrencyCode;

    public float $rate;

    public DateTimeInterface $date;
}
