<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Factory\CurrencyFactoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Service\Dto\CurrencyDto;

class CurrencyUpdateService implements CurrencyUpdateServiceInterface
{
    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository, private readonly CurrencyFactoryInterface $currencyFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function updateCurrencies(array $currencies): void
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

        if ($newCurrencies === []) {
            return;
        }

        $this->currencyRepository->save($newCurrencies);
    }
}
