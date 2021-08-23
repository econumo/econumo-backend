<?php

declare(strict_types=1);

namespace App\Application\User\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"email", "password"}
 * )
 */
class RegisterUserV1RequestDto
{
    /**
     * @SWG\Property(example="example@test.com")
     */
    public string $email;

    /**
     * @SWG\Property(example="pass")
     */
    public string $password;

    /**
     * @SWG\Property(example="John")
     */
    public string $name;
}
