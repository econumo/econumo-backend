<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Repository\CurrencyRepositoryInterface;
use DateTimeInterface;

readonly class CurrencyService implements CurrencyServiceInterface
{
    public function __construct(private string $baseCurrency, private CurrencyRepositoryInterface $currencyRepository)
    {
    }

    public function getBaseCurrency(): Currency
    {
        return $this->currencyRepository->getByCode(new CurrencyCode($this->baseCurrency));
    }

    public function getAvailableCurrencies(): array
    {
        return $this->currencyRepository->getAll();
    }

    public function getChanged(DateTimeInterface $lastUpdate): array
    {
        $currencies = $this->currencyRepository->getAll();
        $result = [];
        foreach ($currencies as $currency) {
            if ($currency->getCreatedAt() > $lastUpdate) {
                $result[] = $currency;
            }
        }

        return $result;
    }
}
