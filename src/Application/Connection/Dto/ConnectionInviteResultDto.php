<?php

declare(strict_types=1);


namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"code", "expiredAt"}
 * )
 */
class ConnectionInviteResultDto
{
    /**
     * Code
     * @var string
     * @SWG\Property(example="2b855")
     */
    public string $code;

    /**
     * Expired at
     * @var string
     * @SWG\Property(example="2021-01-01 12:15:00")
     */
    public string $expiredAt;
}
