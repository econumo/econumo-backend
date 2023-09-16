<?php

declare(strict_types=1);

namespace App\Application\System\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"code", "rate"}
 * )
 */
class CurrencyRateRequestDto
{
    /**
     * @OA\Property(example="EUR")
     */
    public string $code;

    /**
     * @OA\Property(example="1.01")
     */
    public float $rate;
}
