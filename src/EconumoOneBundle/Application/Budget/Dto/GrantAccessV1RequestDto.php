<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"budgetId", "userId", "role"}
 * )
 */
class GrantAccessV1RequestDto
{
    /**
     * @OA\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $budgetId;

    /**
     * @OA\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $userId;

    /**
     * @OA\Property(example="admin")
     */
    public string $role;
}
