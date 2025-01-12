<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;

readonly class AverageCurrencyRateDto
{
    public function __construct(
        public Id $currencyId,
        public DecimalNumber $value
    ) {
    }
}