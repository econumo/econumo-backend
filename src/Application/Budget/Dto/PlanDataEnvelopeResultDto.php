<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "budget", "available"}
 * )
 */
class PlanDataEnvelopeResultDto
{
    /**
     * @OA\Property(example="f8ffe0ef-981a-41ab-9f53-8915a94f96ce")
     */
    public string $id;

    /**
     * @OA\Property(example="13.07")
     */
    public float $budget;

    /**
     * @OA\Property(example="13.07")
     */
    public float $available;
}
