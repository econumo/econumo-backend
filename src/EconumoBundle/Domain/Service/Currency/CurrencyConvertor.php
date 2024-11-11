<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Currency;


use App\EconumoBundle\Domain\Entity\CurrencyRate;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Exception\DomainException;
use App\EconumoBundle\Domain\Repository\CurrencyRateRepositoryInterface;
use App\EconumoBundle\Domain\Repository\CurrencyRepositoryInterface;
use App\EconumoBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoBundle\Domain\Service\Currency\CurrencyRateServiceInterface;
use App\EconumoBundle\Domain\Service\Currency\Dto\CurrencyConvertorDto;
use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoBundle\Domain\Service\Dto\FullCurrencyRateDto;
use App\EconumoBundle\Domain\Service\Currency\CurrencyConvertorInterface;
use DateInterval;
use DatePeriod;
use DateTimeInterface;

class CurrencyConvertor implements CurrencyConvertorInterface
{
    private CurrencyCode $baseCurrency;

    private ?Id $baseCurrencyId;

    public function __construct(
        string $baseCurrency,
        readonly private UserRepositoryInterface $userRepository,
        readonly private CurrencyRateRepositoryInterface $currencyRateRepository,
        readonly private CurrencyRateServiceInterface $currencyRateService,
        readonly private CurrencyRepositoryInterface $currencyRepository,
        readonly private DatetimeServiceInterface $datetimeService
    ) {
        $this->baseCurrency = new CurrencyCode($baseCurrency);
        $this->baseCurrencyId = null;
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

    /**
     * @inheritDoc
     */
    public function bulkConvert(array $items): array
    {
        $conversionNeeded = [];
        $currentPeriodStart = $this->datetimeService->getCurrentMonthStart();
        $currentPeriodStartIndex = $currentPeriodStart->format('Ym');
        $currentPeriodEnd = $this->datetimeService->getNextMonthStart();
        $conversionNeeded[$currentPeriodStartIndex] = [$currentPeriodStart, $currentPeriodEnd];
        foreach ($items as $item) {
            if (is_array($item)) {
                foreach ($item as $subItem) {
                    $index = $subItem->periodStart->format('Ym');
                    if ($subItem->fromCurrencyId->isEqual($subItem->toCurrencyId)) {
                        continue;
                    }
                    if (array_key_exists($index, $conversionNeeded)) {
                        continue;
                    }
                    $conversionNeeded[$index] = [$subItem->periodStart, $subItem->periodEnd];
                }
            } else {
                $index = $item->periodStart->format('Ym');
                if ($item->fromCurrencyId->isEqual($item->toCurrencyId)) {
                    continue;
                }
                if (array_key_exists($index, $conversionNeeded)) {
                    continue;
                }
                $conversionNeeded[$index] = [$item->periodStart, $item->periodEnd];
            }
        }
        if (count($conversionNeeded) === 0) {
            return [];
        }

        $rates = [];
        foreach ($conversionNeeded as $index => $dateRange) {
            $rates[$index] = $this->currencyRateService->getAverageCurrencyRates($dateRange[0], $dateRange[1]);
        }

        $result = [];
        foreach ($items as $key => $dto) {
            $result[$key] = .0;
            if ($dto instanceof CurrencyConvertorDto) {
                $existingKey = array_key_exists($dto->periodStart->format('Ym'), $rates) ? $dto->periodStart->format('Ym') : $currentPeriodStartIndex;
                $result[$key] = $this->convertInternalById($rates[$existingKey], $dto->fromCurrencyId, $dto->toCurrencyId, $dto->amount);
            } else {
                /** @var CurrencyConvertorDto $item */
                foreach ($dto as $item) {
                    $existingKey = array_key_exists($item->periodStart->format('Ym'), $rates) ? $item->periodStart->format('Ym') : $currentPeriodStartIndex;
                    $result[$key] += $this->convertInternalById($rates[$existingKey], $item->fromCurrencyId, $item->toCurrencyId, $item->amount);
                }
            }
        }

        return $result;
    }

    /**
     * @param FullCurrencyRateDto[] $rates
     * @param CurrencyCode $originalCurrency
     * @param CurrencyCode $resultCurrency
     * @param float $sum
     * @return float
     */
    private function convertInternal(
        array $rates,
        CurrencyCode $originalCurrency,
        CurrencyCode $resultCurrency,
        float $sum
    ): float {
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

    /**
     * @param FullCurrencyRateDto[] $rates
     * @param Id $originalCurrencyId
     * @param Id $resultCurrencyId
     * @param float $amount
     * @return float
     */
    private function convertInternalById(
        array $rates,
        Id $originalCurrencyId,
        Id $resultCurrencyId,
        float $amount
    ): float {
        if ($originalCurrencyId->isEqual($resultCurrencyId)) {
            return $amount;
        }

        if ($this->baseCurrencyId) {
            $baseCurrencyId = $this->baseCurrencyId;
        } else {
            $baseCurrencyId = null;
            foreach ($rates as $rate) {
                if ($rate->currencyCode->isEqual($this->baseCurrency)) {
                    $baseCurrencyId = $rate->currencyId;
                    $this->baseCurrencyId = $baseCurrencyId;
                    break;
                }
            }
        }
        if (!$baseCurrencyId) {
            throw new DomainException('Base Currency not found');
        }

        $result = $amount;
        if (!$originalCurrencyId->isEqual($baseCurrencyId)) {
            foreach ($rates as $rate) {
                if ($rate->currencyId->isEqual($originalCurrencyId)) {
                    $result /= $rate->rate;
                    break;
                }
            }
        }

        if (!$resultCurrencyId->isEqual($baseCurrencyId)) {
            foreach ($rates as $rate) {
                if ($rate->currencyId->isEqual($resultCurrencyId)) {
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
