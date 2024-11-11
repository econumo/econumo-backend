<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Currency;

use App\EconumoBundle\Domain\Entity\Currency;
use App\EconumoBundle\Domain\Entity\CurrencyRate;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Exception\NotFoundException;
use App\EconumoBundle\Domain\Factory\CurrencyRateFactoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Service\Currency\CurrencyRatesUpdateServiceInterface;
use App\EconumoBundle\Domain\Service\Dto\CurrencyRateDto;

class CurrencyRatesUpdateService implements CurrencyRatesUpdateServiceInterface
{
    public function __construct(private readonly CurrencyRateRepositoryInterface $currencyRateRepository, private readonly CurrencyRepositoryInterface $currencyRepository, private readonly CurrencyRateFactoryInterface $currencyRateFactory)
    {
    }

    /**
     * @inheritDoc
     */
    public function updateCurrencyRates(array $currencyRates): int
    {
        $currencies = $this->currencyRepository->getAll();

        /** @var CurrencyRate[] $forUpdate */
        $forUpdate = [];
        foreach ($currencyRates as $currencyRateDto) {
            $item = $this->currencyRateFactory->create(
                $currencyRateDto->date,
                $this->getCurrency($currencies, $currencyRateDto->code),
                $this->getCurrency($currencies, $currencyRateDto->base),
                $currencyRateDto->rate
            );
            try {
                if ($this->currencyRateRepository->get($item->getCurrency()->getId(), $item->getPublishedAt())) {
                    continue;
                }
            } catch (NotFoundException) {
            }

            $forUpdate[] = $item;
        }

        if ($forUpdate !== []) {
            $this->currencyRateRepository->save($forUpdate);
        }

        return count($forUpdate);
    }

    /**
     * @param Currency[] $currencies
     */
    private function getCurrency(array $currencies, CurrencyCode $code): Currency
    {
        foreach ($currencies as $currency) {
            if ($currency->getCode()->isEqual($code)) {
                return $currency;
            }
        }

        throw new NotFoundException('Not found currency ' . $code->getValue());
    }
}
