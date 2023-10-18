<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"periodStart", "periodEnd", "balances", "exchanges", "currencyRates", "envelopes", "categories", "tags"}
 * )
 */
class PlanDataResultDto
{
    /**
     * @OA\Property(example="2020-01-01 00:00:00")
     */
    public string $periodStart;

    /**
     * @OA\Property(example="2020-01-31 23:59:59")
     */
    public string $periodEnd;

    /**
     * @var PlanDataBalanceResultDto[]
     * @OA\Property()
     */
    public array $balances = [];

    /**
     * @var PlanDataExchangeResultDto[]
     * @OA\Property()
     */
    public array $exchanges = [];

    /**
     * @var PlanDataCurrencyRateResultDto[]
     * @OA\Property()
     */
    public array $currencyRates = [];

    /**
     * @var PlanDataEnvelopeResultDto[]
     * @OA\Property()
     */
    public array $envelopes = [];

    /**
     * @var PlanDataCategoryResultDto[]
     * @OA\Property()
     */
    public array $categories = [];

    /**
     * @var PlanDataTagResultDto[]
     * @OA\Property()
     */
    public array $tags = [];
}
