<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetEntityAmountSpentDto
{
    public function __construct(
        public Id $currencyId,
        public float $amount,
        public DateTimeInterface $periodStart,
        public DateTimeInterface $periodEnd,
    ) {
    }
}