<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Dto;

use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Service\Dto\PlanDataBalanceDto;
use App\EconumoOneBundle\Domain\Service\Dto\PlanDataCategoryDto;
use App\EconumoOneBundle\Domain\Service\Dto\PlanDataCurrencyRateDto;
use App\EconumoOneBundle\Domain\Service\Dto\PlanDataEnvelopeDto;
use App\EconumoOneBundle\Domain\Service\Dto\PlanDataExchangeDto;
use App\EconumoOneBundle\Domain\Service\Dto\PlanDataTagDto;
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
