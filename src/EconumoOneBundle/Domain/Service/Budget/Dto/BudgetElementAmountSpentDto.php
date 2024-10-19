<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetElementAmountSpentDto
{
    public function __construct(
        public Id $currencyId,
        public float $amount,
        public DateTimeInterface $periodStart,
        public DateTimeInterface $periodEnd,
    ) {
    }
}