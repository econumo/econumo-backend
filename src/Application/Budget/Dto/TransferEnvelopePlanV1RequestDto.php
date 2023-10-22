<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"fromEnvelopeId", "toEnvelopeId", "period", "amount"},
 * )
 */
class TransferEnvelopePlanV1RequestDto
{
    /**
     * @OA\Property(example="f8ffe0ef-981a-41ab-9f53-8915a94f96ce")
     */
    public string $fromEnvelopeId;

    /**
     * @OA\Property(example="f8ffe0ef-981a-41ab-9f53-8915a94f96ce")
     */
    public string $toEnvelopeId;

    /**
     * @OA\Property(example="2020-01-01 00:00:00")
     */
    public string $period;

    /**
     * @OA\Property(example="0.0")
     */
    public float $amount;
}
