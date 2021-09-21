<?php

declare(strict_types=1);

namespace App\Application\User\Updates\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"lastUpdate"}
 * )
 */
class CheckUpdatesV1RequestDto
{
    /**
     * @SWG\Property(example="2021-01-01 12:15:15")
     */
    public string $lastUpdate;
}
