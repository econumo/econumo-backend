<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Factory\CurrencyFactoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Service\Dto\CurrencyDto;

class CurrencyUpdateService implements CurrencyUpdateServiceInterface
{
    private CurrencyRepositoryInterface $currencyRepository;
    private CurrencyFactoryInterface $currencyFactory;

    public function __construct(
        CurrencyRepositoryInterface $currencyRepository,
        CurrencyFactoryInterface $currencyFactory
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->currencyFactory = $currencyFactory;
    }

    public function updateCurrencies(CurrencyDto ...$currencies): void
    {
        $savedCurrencies = $this->currencyRepository->getAll();
        $newCurrencies = [];
        foreach ($currencies as $currencyDto) {
            $found = false;
            foreach ($savedCurrencies as $savedCurrency) {
                if ($savedCurrency->getCode()->isEqual($currencyDto->code)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $newCurrencies[] = $this->currencyFactory->create($currencyDto->code);
            }
        }
        if (!count($newCurrencies)) {
            return;
        }

        $this->currencyRepository->save(...$newCurrencies);
    }
}
