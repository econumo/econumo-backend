<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Service\Dto\CurrencyDto;

interface CurrencyUpdateServiceInterface
{
    public function updateCurrencies(CurrencyDto ...$currencies): void;
}
