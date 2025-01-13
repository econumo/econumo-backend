<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Factory;

use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Factory\CurrencyFactoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Exception\MissingResourceException;

readonly class CurrencyFactory implements CurrencyFactoryInterface
{
    public function __construct(
        private CurrencyRepositoryInterface $currencyRepository,
        private DatetimeServiceInterface $datetimeService
    ) {
    }

    public function create(CurrencyCode $code): Currency
    {
        try {
            $symbol = Currencies::getSymbol($code->getValue());
            $fraction = Currencies::getFractionDigits($code->getValue());
        } catch (MissingResourceException) {
            $symbol = $code->getValue();
            $fraction = DecimalNumber::SCALE;
        }

        return new Currency(
            $this->currencyRepository->getNextIdentity(),
            $code,
            $symbol,
            $fraction,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
