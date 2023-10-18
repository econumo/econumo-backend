<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\Dto\FullCurrencyRateDto;
use DateTimeInterface;

readonly class CurrencyConvertor implements CurrencyConvertorInterface
{
    private CurrencyCode $baseCurrency;

    public function __construct(
        string $baseCurrency,
        private UserRepositoryInterface $userRepository,
        private CurrencyRateRepositoryInterface $currencyRateRepository,
        private CurrencyRateServiceInterface $currencyRateService,
        private CurrencyRepositoryInterface $currencyRepository,
    ) {
        $this->baseCurrency = new CurrencyCode($baseCurrency);
    }

    public function convertForUser(Id $userId, CurrencyCode $originalCurrency, float $sum): float
    {
        $user = $this->userRepository->get($userId);
        $userCurrency = $user->getCurrency();
        return $this->convert($originalCurrency, $userCurrency, $sum);
    }

    public function convert(CurrencyCode $originalCurrency, CurrencyCode $resultCurrency, float $sum): float
    {
        if ($originalCurrency->isEqual($resultCurrency)) {
            return $sum;
        }

        $rates = [];
        foreach ($this->currencyRateRepository->getAll() as $currencyRate) {
            $rates[] = $this->transformCurrencyToDto($currencyRate);
        }

        return $this->convertInternal($rates, $originalCurrency, $resultCurrency, $sum);
    }

    public function convertForPeriod(
        Id $fromCurrencyId,
        Id $toCurrencyId,
        float $sum,
        DateTimeInterface $periodStart,
        DateTimeInterface $periodEnd
    ): float {
        if ($fromCurrencyId->isEqual($toCurrencyId)) {
            return $sum;
        }

        $fromCurrency = $this->currencyRepository->get($fromCurrencyId);
        $toCurrency = $this->currencyRepository->get($toCurrencyId);
        $rates = $this->currencyRateService->getAverageCurrencyRates($periodStart, $periodEnd);
        return $this->convertInternal($rates, $fromCurrency->getCode(), $toCurrency->getCode(), $sum);
    }

    /**
     * @param FullCurrencyRateDto[] $rates
     * @param CurrencyCode $originalCurrency
     * @param CurrencyCode $resultCurrency
     * @param float $sum
     * @return float
     */
    private function convertInternal(array $rates, CurrencyCode $originalCurrency, CurrencyCode $resultCurrency, float $sum): float
    {
        if ($originalCurrency->isEqual($resultCurrency)) {
            return $sum;
        }

        $result = $sum;
        if (!$originalCurrency->isEqual($this->baseCurrency)) {
            foreach ($rates as $rate) {
                if ($rate->currencyCode->isEqual($originalCurrency)) {
                    $result /= $rate->rate;
                    break;
                }
            }
        }

        if (!$resultCurrency->isEqual($this->baseCurrency)) {
            foreach ($rates as $rate) {
                if ($rate->currencyCode->isEqual($resultCurrency)) {
                    $result *= $rate->rate;
                    break;
                }
            }
        }

        return $result;
    }

    private function transformCurrencyToDto(CurrencyRate $currency): FullCurrencyRateDto
    {
        $dto = new FullCurrencyRateDto();
        $dto->currencyId = $currency->getCurrency()->getId();
        $dto->currencyCode = $currency->getCurrency()->getCode();
        $dto->baseCurrencyId = $currency->getBaseCurrency()->getId();
        $dto->baseCurrencyCode = $currency->getBaseCurrency()->getCode();
        $dto->rate = $currency->getRate();
        $dto->date = $currency->getPublishedAt();

        return $dto;
    }
}
