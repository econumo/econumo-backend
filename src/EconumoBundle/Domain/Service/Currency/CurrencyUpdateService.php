<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Currency;


use App\EconumoBundle\Domain\Factory\CurrencyFactoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Service\Dto\CurrencyDto;
use App\EconumoBundle\Domain\Service\Currency\CurrencyUpdateServiceInterface;

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
