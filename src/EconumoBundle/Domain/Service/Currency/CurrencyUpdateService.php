<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Currency;


use App\EconumoBundle\Domain\Factory\CurrencyFactoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Service\Dto\CurrencyDto;
use App\EconumoBundle\Domain\Service\Currency\CurrencyUpdateServiceInterface;

class CurrencyUpdateService implements CurrencyUpdateServiceInterface
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly CurrencyFactoryInterface $currencyFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function updateCurrencies(array $currencies, bool $restoreFraction = false): void
    {
        $savedCurrencies = $this->currencyRepository->getAll();
        $updatedCurrencies = [];
        foreach ($currencies as $currencyDto) {
            $found = false;
            foreach ($savedCurrencies as $savedCurrency) {
                if ($savedCurrency->getCode()->isEqual($currencyDto->code)) {
                    $found = true;
                    if ($restoreFraction) {
                        $savedCurrency->restoreSystemFraction();
                        $updatedCurrencies[] = $savedCurrency;
                    }

                    break;
                }
            }

            if (!$found) {
                $updatedCurrencies[] = $this->currencyFactory->create($currencyDto->code);
            }
        }

        if ($updatedCurrencies === []) {
            return;
        }

        $this->currencyRepository->save($updatedCurrencies);
    }
}
