<?php

declare(strict_types=1);


namespace App\Domain\Service\Currency\Dto;


use App\Domain\Entity\ValueObject\Id;

readonly class CurrencyConvertorDto
{
    public function __construct(
        public Id $fromCurrencyId,
        public Id $toCurrencyId,
        public float $amount
    ) {
    }
}
