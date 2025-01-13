<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Currency;


use App\EconumoBundle\Domain\Service\Dto\CurrencyDto;

interface CurrencyUpdateServiceInterface
{
    /**
     * @param CurrencyDto[] $currencies
     * @param bool $restoreFractionDigits
     * @return void
     */
    public function updateCurrencies(array $currencies, bool $restoreFractionDigits = false): void;
}
