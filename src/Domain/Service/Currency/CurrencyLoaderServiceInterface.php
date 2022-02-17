<?php

declare(strict_types=1);

namespace App\Domain\Service\Currency;

use App\Domain\Service\Dto\CurrencyDto;

interface CurrencyLoaderServiceInterface
{
    /**
     * @return CurrencyDto[]
     */
    public function loadCurrencies(): array;
}
