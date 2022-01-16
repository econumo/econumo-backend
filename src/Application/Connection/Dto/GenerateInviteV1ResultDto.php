<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class GenerateInviteV1ResultDto
{
    /**
     * @SWG\Property()
     */
    public ConnectionInviteResultDto $item;
}
