<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Factory;

use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\CurrencyRate;
use App\EconumoBundle\Domain\Factory\CurrencyRateFactoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use DateTimeInterface;

class CurrencyRateFactory implements CurrencyRateFactoryInterface
{
    public function __construct(private readonly CurrencyRateRepositoryInterface $currencyRateRepository)
    {
    }

    public function create(
        DateTimeInterface $date,
        Currency $currency,
        Currency $baseCurrency,
        float $rate
    ): CurrencyRate {
        return new CurrencyRate(
            $this->currencyRateRepository->getNextIdentity(),
            $currency,
            $baseCurrency,
            $rate,
            $date
        );
    }
}
