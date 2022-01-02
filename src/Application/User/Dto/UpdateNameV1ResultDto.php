<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"token"}
 * )
 */
class UpdateNameV1ResultDto
{
    /**
     * Id
     * @SWG\Property(example="jwt-token")
     */
    public string $token;
}
