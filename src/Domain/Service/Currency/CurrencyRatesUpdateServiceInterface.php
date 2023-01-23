<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Service\Dto\CurrencyRateDto;

interface CurrencyRatesUpdateServiceInterface
{
    /**
     * @param CurrencyRateDto[] $currencyRates
     * @return int
     */
    public function updateCurrencyRates(array $currencyRates): int;
}
