<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Currency;

use App\EconumoOneBundle\Domain\Entity\Currency;
use DateTimeInterface;

interface CurrencyServiceInterface
{
    public function getBaseCurrency(): Currency;

    /**
     * @return Currency[]
     */
    public function getAvailableCurrencies(): array;
}
