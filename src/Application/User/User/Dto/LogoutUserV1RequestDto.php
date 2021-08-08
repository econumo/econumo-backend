<?php

declare(strict_types=1);

namespace App\Application\User\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class LogoutUserV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;
}
