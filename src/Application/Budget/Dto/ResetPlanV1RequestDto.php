<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "periodStart"}
 * )
 */
class ResetPlanV1RequestDto
{
    /**
     * @OA\Property(example="229f97a8-e9c9-4d45-8405-91b7f315f014")
     */
    public string $id;

    /**
     * @OA\Property(example="2021-01-01 00:00:00")
     */
    public string $periodStart;
}
