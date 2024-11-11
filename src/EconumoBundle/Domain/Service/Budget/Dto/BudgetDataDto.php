<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

readonly class BudgetDataDto
{
    public function __construct(
        public Id $budgetId,
        public DateTimeInterface $periodStart,
        public DateTimeInterface $periodEnd,
        /** @var CurrencyBalanceDto[] */
        public array $currencyBalances,
        /** @var AverageCurrencyRateDto[] */
        public array $averageCurrencyRates,
        /** @var BudgetElementAmountDto[] */
        public array $entityAmounts
    ) {
    }
}
