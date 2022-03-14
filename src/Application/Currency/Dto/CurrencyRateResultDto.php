<?php

declare(strict_types=1);


namespace App\Application\Currency\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"currencyId", "baseCurrencyId", "rate", "updatedAt"}
 * )
 */
class CurrencyRateResultDto
{
    /**
     * Currency id
     * @var string
     * @SWG\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * Base currency id
     * @var string
     * @SWG\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $baseCurrencyId;

    /**
     * Currency rate
     * @var float
     * @SWG\Property(example="0.123")
     */
    public float $rate;

    /**
     * Updated at
     * @var string
     * @SWG\Property(example="2021-01-01 12:15:00")
     */
    public string $updatedAt;
}
