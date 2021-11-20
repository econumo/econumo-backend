<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"username", "password"}
 * )
 */
class LoginUserV1RequestDto
{
    /**
     * @var string
     * @SWG\Property(example="username")
     */
    public string $username;

    /**
     * @var string
     * @SWG\Property(example="password")
     */
    public string $password;
}
