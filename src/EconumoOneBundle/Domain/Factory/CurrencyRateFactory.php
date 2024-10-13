<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Factory;

use App\EconumoOneBundle\Domain\Entity\Currency;
use App\EconumoOneBundle\Domain\Entity\CurrencyRate;
use App\EconumoOneBundle\Domain\Factory\CurrencyRateFactoryInterface;
use App\EconumoOneBundle\Domain\Repository\CurrencyRateRepositoryInterface;
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
