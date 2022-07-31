<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id"}
 * )
 */
class RegisterUserV1ResultDto
{
    /**
     * Id
     * @OA\Property(example="c3cb6a87-3e30-4f2f-9ffa-3c2d2276a6fc")
     */
    public string $id;
}
