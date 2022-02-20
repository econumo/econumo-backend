<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Currency;
use App\Domain\Entity\CurrencyRate;
use DateTimeInterface;

interface CurrencyRateFactoryInterface
{
    public function create(
        DateTimeInterface $date,
        Currency $currency,
        Currency $baseCurrency,
        float $rate
    ): CurrencyRate;
}
