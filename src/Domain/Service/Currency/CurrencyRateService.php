<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;

use App\Domain\Entity\CurrencyRate;
use App\Domain\Repository\CurrencyRateRepositoryInterface;

class CurrencyRateService implements CurrencyRateServiceInterface
{
    private CurrencyRateRepositoryInterface $currencyRateRepository;

    public function __construct(CurrencyRateRepositoryInterface $currencyRateRepository)
    {
        $this->currencyRateRepository = $currencyRateRepository;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyRates(\DateTimeInterface $dateTime): array
    {
        return $this->currencyRateRepository->getAll($dateTime);
    }

    /**
     * @inheritDoc
     */
    public function getLatestCurrencyRates(): array
    {
        return $this->currencyRateRepository->getAll();
    }
}
