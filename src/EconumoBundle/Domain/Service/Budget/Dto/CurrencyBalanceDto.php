<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;

readonly class CurrencyBalanceDto
{
    public function __construct(
        public Id $currencyId,
        public ?float $startBalance,
        public ?float $endBalance,
        public ?float $income,
        public ?float $expenses,
        public ?float $exchanges,
        public ?float $holdings,
    ) {
    }
}