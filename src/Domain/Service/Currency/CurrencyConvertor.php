<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\Currency\Dto\CurrencyConvertorDto;
use App\Domain\Service\Dto\FullCurrencyRateDto;
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
        readonly private CurrencyRepositoryInterface $currencyRepository
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
    public function bulkConvert(DateTimeInterface $periodStart, DateTimeInterface $periodEnd, array $items): array
    {
        $rates = $this->currencyRateService->getAverageCurrencyRates($periodStart, $periodEnd);

        $result = [];
        foreach ($items as $key => $dto) {
            $result[$key] = .0;
            if ($dto instanceof CurrencyConvertorDto) {
                $result[$key] = $this->convertInternalById($rates, $dto->fromCurrencyId, $dto->toCurrencyId, $dto->amount);
            } else {
                /** @var CurrencyConvertorDto $item */
                foreach ($dto as $item) {
                    $result[$key] += $this->convertInternalById($rates, $item->fromCurrencyId, $item->toCurrencyId, $item->amount);
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
