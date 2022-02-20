<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Service\Dto\CurrencyRateDto;
use DateTimeInterface;

interface CurrencyRatesLoaderServiceInterface
{
    /**
     * @return CurrencyRateDto[]
     */
    public function loadCurrencyRates(DateTimeInterface $date): array;
}
