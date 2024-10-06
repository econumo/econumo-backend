<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Budget\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\EconumoBundle\Domain\Service\Budget\Dto\CurrencyBalanceDto;
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
        /** @var BudgetEntityAmountDto[] */
        public array $entityAmounts
    ) {
    }
}
