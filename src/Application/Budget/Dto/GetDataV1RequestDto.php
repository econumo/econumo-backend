<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "periodStart", "periodType", "numberOfPeriods"},
 * )
 */
class GetDataV1RequestDto
{
    /**
     * @OA\Property(example="16c88ac2-b548-4446-9e27-51a28156b299")
     */
    public string $id;

    /**
     * @OA\Property(example="2021-01-01 00:00:00")
     */
    public string $periodStart;

    /**
     * @OA\Property(example="month")
     */
    public string $periodType;

    /**
     * @OA\Property(example="1")
     */
    public int $numberOfPeriods;
}
