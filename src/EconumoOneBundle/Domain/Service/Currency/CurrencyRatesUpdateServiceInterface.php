<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Currency;

use App\EconumoOneBundle\Domain\Service\Dto\CurrencyRateDto;

interface CurrencyRatesUpdateServiceInterface
{
    /**
     * @param CurrencyRateDto[] $currencyRates
     * @return int
     */
    public function updateCurrencyRates(array $currencyRates): int;
}
