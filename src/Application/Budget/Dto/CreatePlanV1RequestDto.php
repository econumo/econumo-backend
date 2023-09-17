<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name"}
 * )
 */
class CreatePlanV1RequestDto
{
    /**
     * @OA\Property(example="123")
     */
    public string $id;

    /**
     * @OA\Property(example="Amazon")
     */
    public string $name;
}
