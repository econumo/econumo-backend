<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

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