<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"username"}
 * )
 */
class RemindPasswordV1RequestDto
{
    /**
     * @SWG\Property(example="john@snow.test")
     */
    public string $username;
}
