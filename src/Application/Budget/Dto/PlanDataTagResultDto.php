<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "currencyId", "amount"}
 * )
 */
class PlanDataTagResultDto
{
    /**
     * @OA\Property(example="4b53d029-c1ed-46ad-8d86-1049542f4a7e")
     */
    public string $id;

    /**
     * @OA\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * @OA\Property(example="13.07")
     */
    public float $amount;
}
