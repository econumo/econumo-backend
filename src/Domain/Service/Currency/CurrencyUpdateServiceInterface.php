<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Service\Dto\CurrencyDto;

interface CurrencyUpdateServiceInterface
{
    /**
     * @param CurrencyDto[] $currencies
     * @return void
     */
    public function updateCurrencies(array $currencies): void;
}
