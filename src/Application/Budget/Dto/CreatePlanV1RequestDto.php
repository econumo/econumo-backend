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
     * @OA\Property(example="16c88ac2-b548-4446-9e27-51a28156b299")
     */
    public string $id;

    /**
     * @OA\Property(example="Amazon")
     */
    public string $name;
}
