<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\CurrencyRateRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class CurrencyConvertor implements CurrencyConvertorInterface
{
    private CurrencyCode $baseCurrency;
    private UserRepositoryInterface $userRepository;
    private CurrencyRateRepositoryInterface $currencyRateRepository;

    public function __construct(
        string $baseCurrency,
        UserRepositoryInterface $userRepository,
        CurrencyRateRepositoryInterface $currencyRateRepository
    ) {
        $this->userRepository = $userRepository;
        $this->currencyRateRepository = $currencyRateRepository;
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

        $rates = $this->currencyRateRepository->getAll();
        $result = $sum;
        if (!$originalCurrency->isEqual($this->baseCurrency)) {
            foreach ($rates as $rate) {
                if ($rate->getCurrency()->getCode()->isEqual($originalCurrency)) {
                    $result = $result / $rate->getRate();
                    break;
                }
            }
        }

        if (!$resultCurrency->isEqual($this->baseCurrency)) {
            foreach ($rates as $rate) {
                if ($rate->getCurrency()->getCode()->isEqual($resultCurrency)) {
                    $result = $result * $rate->getRate();
                    break;
                }
            }
        }

        return $result;
    }
}
