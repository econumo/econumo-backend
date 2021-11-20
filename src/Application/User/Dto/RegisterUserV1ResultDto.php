<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

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
     * @SWG\Property(example="c3cb6a87-3e30-4f2f-9ffa-3c2d2276a6fc")
     */
    public string $id;
}
