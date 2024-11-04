<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\Currency\Dto;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class CurrencyConvertorDto
{
    public function __construct(
        public DateTimeInterface $periodStart,
        public DateTimeInterface $periodEnd,
        public Id $fromCurrencyId,
        public Id $toCurrencyId,
        public float $amount,
    ) {
    }
}
