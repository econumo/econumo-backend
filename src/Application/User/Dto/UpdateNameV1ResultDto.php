<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"token"}
 * )
 */
class UpdateNameV1ResultDto
{
    /**
     * Id
     * @OA\Property(example="jwt-token")
     */
    public string $token;
}
