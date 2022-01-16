<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"code"}
 * )
 */
class AcceptInviteV1RequestDto
{
    /**
     * @SWG\Property(example="2b345")
     */
    public string $code;
}
