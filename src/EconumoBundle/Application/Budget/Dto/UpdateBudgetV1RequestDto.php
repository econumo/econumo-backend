<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name"},
 * )
 */
class UpdateBudgetV1RequestDto
{
    /**
     * @OA\Property(example="9b29b760-ddca-46fb-a754-8743fc2c49a7")
     */
    public string $id;

    /**
     * @OA\Property(example="My budget")
     */
    public string $name;
}
