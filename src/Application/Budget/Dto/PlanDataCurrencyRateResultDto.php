<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    required={"currencyId", "baseCurrencyId", "rate", "date"},
 * )
 */
class PlanDataCurrencyRateResultDto
{
    /**
     * Currency id
     * @var string
     * @OA\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * Base currency id
     * @var string
     * @OA\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $baseCurrencyId;

    /**
     * Currency rate
     * @var float
     * @OA\Property(example="0.123")
     */
    public float $rate;

    /**
     * Currency rate date
     * @OA\Property(example="2020-01-01")
     */
    public string $date;
}
