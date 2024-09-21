<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\Id;

readonly class AverageCurrencyRateDto
{
    public function __construct(
        public Id $currencyId,
        public float $value
    ) {
    }
}