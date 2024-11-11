<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service\Dto;

use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Service\Dto\PlanDataBalanceDto;
use App\EconumoBundle\Domain\Service\Dto\PlanDataCategoryDto;
use App\EconumoBundle\Domain\Service\Dto\PlanDataCurrencyRateDto;
use App\EconumoBundle\Domain\Service\Dto\PlanDataEnvelopeDto;
use App\EconumoBundle\Domain\Service\Dto\PlanDataExchangeDto;
use App\EconumoBundle\Domain\Service\Dto\PlanDataTagDto;
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
