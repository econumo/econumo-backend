<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Domain\Entity\Currency;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Factory\CurrencyFactoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;

class CurrencyFactory implements CurrencyFactoryInterface
{
    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository, private readonly DatetimeServiceInterface $datetimeService)
    {
    }

    public function create(CurrencyCode $code): Currency
    {
        try {
            $symbol = Currencies::getSymbol($code->getValue());
        } catch (MissingResourceException) {
            $symbol = $code->getValue();
        }

        return new Currency(
            $this->currencyRepository->getNextIdentity(),
            $code,
            $symbol,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
