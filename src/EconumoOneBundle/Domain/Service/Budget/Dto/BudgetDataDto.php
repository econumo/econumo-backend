<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\AverageCurrencyRateDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetEntityAmountDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\CurrencyBalanceDto;
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
