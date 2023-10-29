<?php

declare(strict_types=1);

namespace App\Domain\Service\Dto;

use App\Domain\Entity\ValueObject\Id;
use DateTimeInterface;

class PlanDataDto
{
    public Id $id;

    public DateTimeInterface $periodStart;

    public DateTimeInterface $periodEnd;

    /**
     * @var PlanDataBalanceDto[]
     */
    public array $balances = [];

    /**
     * @var PlanDataExchangeDto[]
     */
    public array $exchanges = [];

    /**
     * @var PlanDataCurrencyRateDto[]
     */
    public array $currencyRates = [];

    /**
     * @var PlanDataEnvelopeDto[]
     */
    public array $envelopes = [];

    /**
     * @var PlanDataCategoryDto[]
     */
    public array $categories = [];

    /**
     * @var PlanDataTagDto[]
     */
    public array $tags = [];
}
