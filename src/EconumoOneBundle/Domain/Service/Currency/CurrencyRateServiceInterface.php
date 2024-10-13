<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Currency;

use App\EconumoOneBundle\Domain\Entity\CurrencyRate;
use App\EconumoOneBundle\Domain\Service\Dto\FullCurrencyRateDto;
use DateTimeInterface;

interface CurrencyRateServiceInterface
{
    /**
     * @param DateTimeInterface $dateTime
     * @return CurrencyRate[]
     */
    public function getCurrencyRates(DateTimeInterface $dateTime): array;

    /**
     * @return CurrencyRate[]
     */
    public function getLatestCurrencyRates(): array;

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return FullCurrencyRateDto[]
     */
    public function getAverageCurrencyRates(DateTimeInterface $startDate, DateTimeInterface $endDate): array;
}
