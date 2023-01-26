<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "token"}
 * )
 */
class RegisterUserV1ResultDto
{
    /**
     * Id
     * @OA\Property(example="c3cb6a87-3e30-4f2f-9ffa-3c2d2276a6fc")
     */
    public string $id;

    /**
     * JWT-token
     * @var string
     * @OA\Property(example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c")
     */
    public string $token;
}
