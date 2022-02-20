<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Entity\Currency;

interface CurrencyServiceInterface
{
    public function getBaseCurrency(): Currency;

    /**
     * @return Currency[]
     */
    public function getAvailableCurrencies(): array;
}
