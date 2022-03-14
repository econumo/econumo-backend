<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency;


use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Id;

interface CurrencyConvertorInterface
{
    public function convertForUser(Id $userId, CurrencyCode $originalCurrency, float $sum): float;

    public function convert(CurrencyCode $originalCurrency, CurrencyCode $resultCurrency, float $sum): float;
}
