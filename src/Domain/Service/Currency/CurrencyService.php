<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Repository\CurrencyRepositoryInterface;

class CurrencyService implements CurrencyServiceInterface
{
    private string $baseCurrency;
    private CurrencyRepositoryInterface $currencyRepository;

    public function __construct(
        string $baseCurrency,
        CurrencyRepositoryInterface $currencyRepository
    ) {
        $this->baseCurrency = $baseCurrency;
        $this->currencyRepository = $currencyRepository;
    }

    public function getBaseCurrency(): Currency
    {
        return $this->currencyRepository->getByCode(new CurrencyCode($this->baseCurrency));
    }

    public function getAvailableCurrencies(): array
    {
        return $this->currencyRepository->getAll();
    }
}
