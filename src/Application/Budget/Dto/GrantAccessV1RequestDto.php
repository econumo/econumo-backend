<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"planId", "userId", "role"}
 * )
 */
class GrantAccessV1RequestDto
{
    /**
     * @OA\Property(example="229f97a8-e9c9-4d45-8405-91b7f315f014")
     */
    public string $planId;

    /**
     * @OA\Property(example="77be9577-147b-4f05-9aa7-91d9b159de5b")
     */
    public string $userId;

    /**
     * @OA\Property(example="admin")
     */
    public string $role;
}
