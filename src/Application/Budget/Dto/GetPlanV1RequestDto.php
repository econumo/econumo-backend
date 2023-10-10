<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id"}
 * )
 */
class GetPlanV1RequestDto
{
    /**
     * @OA\Property(example="229f97a8-e9c9-4d45-8405-91b7f315f014")
     */
    public string $id;
}
