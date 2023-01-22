<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Entity\Currency;
use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Exception\NotFoundException;
use App\Domain\Factory\CurrencyRateFactoryInterface;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Service\Dto\CurrencyRateDto;

class CurrencyRatesUpdateService implements CurrencyRatesUpdateServiceInterface
{
    private CurrencyRateRepositoryInterface $currencyRateRepository;

    private CurrencyRepositoryInterface $currencyRepository;

    private CurrencyRateFactoryInterface $currencyRateFactory;

    public function __construct(
        CurrencyRateRepositoryInterface $currencyRateRepository,
        CurrencyRepositoryInterface $currencyRepository,
        CurrencyRateFactoryInterface $currencyRateFactory
    ) {
        $this->currencyRateRepository = $currencyRateRepository;
        $this->currencyRepository = $currencyRepository;
        $this->currencyRateFactory = $currencyRateFactory;
    }

    public function updateCurrencyRates(CurrencyRateDto ...$currencyRates): int
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
            } catch (NotFoundException $notFoundException) {
            }

            $forUpdate[] = $item;
        }

        if ($forUpdate !== []) {
            $this->currencyRateRepository->save(...$forUpdate);
        }

        return count($forUpdate);
    }

    /**
     * @param Currency[] $currencies
     * @param CurrencyCode $code
     * @return Currency
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
