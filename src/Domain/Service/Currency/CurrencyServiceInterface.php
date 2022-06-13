<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Entity\Currency;
use DateTimeInterface;

interface CurrencyServiceInterface
{
    public function getBaseCurrency(): Currency;

    /**
     * @return Currency[]
     */
    public function getAvailableCurrencies(): array;

    /**
     * @param DateTimeInterface $lastUpdate
     * @return Currency[]
     */
    public function getChanged(DateTimeInterface $lastUpdate): array;
}
