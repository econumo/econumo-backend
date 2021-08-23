<?php

declare(strict_types=1);

namespace App\Application\User\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class RegisterUserV1ResultDto
{
    /**
     * Id
     * @SWG\Property(example="user id")
     */
    public string $id;
}
