<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Service\Dto\CurrencyRateDto;

interface CurrencyRatesUpdateServiceInterface
{
    public function updateCurrencyRates(CurrencyRateDto ...$currencyRates): void;
}
