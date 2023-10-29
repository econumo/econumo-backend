<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"currencyId", "budget", "amount"}
 * )
 */
class PlanDataExchangeResultDto
{
    /**
     * @OA\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * @OA\Property(example="13.07")
     */
    public float $budget;

    /**
     * @OA\Property(example="13.07")
     */
    public float $amount;
}
