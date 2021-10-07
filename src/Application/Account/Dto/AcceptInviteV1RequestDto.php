<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"code"}
 * )
 */
class AcceptInviteV1RequestDto
{
    /**
     * @SWG\Property(example="12345")
     */
    public string $code;
}
