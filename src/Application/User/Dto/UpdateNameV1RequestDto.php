<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"name"}
 * )
 */
class UpdateNameV1RequestDto
{
    /**
     * @SWG\Property(example="John")
     */
    public string $name;
}
