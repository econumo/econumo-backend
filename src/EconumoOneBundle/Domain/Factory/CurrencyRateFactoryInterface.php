<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Currency;
use App\EconumoOneBundle\Domain\Entity\CurrencyRate;
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
