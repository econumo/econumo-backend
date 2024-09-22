<?php

declare(strict_types=1);

namespace App\Domain\Service\Budget\Dto;

use App\Domain\Entity\ValueObject\Id;

readonly class BudgetEntityAmountSpentDto
{
    public function __construct(
        public Id $currencyId,
        public float $amount,
    ) {
    }
}