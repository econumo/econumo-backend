<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service\Currency;


use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Service\Currency\Dto\CurrencyConvertorDto;

interface CurrencyConvertorInterface
{
    public function convert(CurrencyCode $originalCurrency, CurrencyCode $resultCurrency, DecimalNumber $sum): DecimalNumber;

    /**
     * @param CurrencyConvertorDto[] $items
     * @return DecimalNumber[]
     */
    public function bulkConvert(array $items): array;
}
