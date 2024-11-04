<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Currency;


use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Currency\Dto\CurrencyConvertorDto;
use DateTimeInterface;

interface CurrencyConvertorInterface
{
    public function convertForUser(Id $userId, CurrencyCode $originalCurrency, float $sum): float;

    public function convert(CurrencyCode $originalCurrency, CurrencyCode $resultCurrency, float $sum): float;

    /**
     * @param CurrencyConvertorDto[] $items
     * @return float[]
     */
    public function bulkConvert(array $items): array;
}
