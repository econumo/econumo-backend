<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Factory;

use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\CurrencyRate;
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
