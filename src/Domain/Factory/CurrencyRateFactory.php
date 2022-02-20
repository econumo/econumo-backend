<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Currency;
use App\Domain\Entity\CurrencyRate;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use DateTimeInterface;

class CurrencyRateFactory implements CurrencyRateFactoryInterface
{
    private CurrencyRateRepositoryInterface $currencyRateRepository;

    public function __construct(
        CurrencyRateRepositoryInterface $currencyRateRepository
    ) {
        $this->currencyRateRepository = $currencyRateRepository;
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
