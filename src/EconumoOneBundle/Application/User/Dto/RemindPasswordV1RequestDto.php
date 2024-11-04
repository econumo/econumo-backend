<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"username"}
 * )
 */
class RemindPasswordV1RequestDto
{
    /**
     * @OA\Property(example="john@econumo.test")
     */
    public string $username;
}
