<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class GenerateInviteV1ResultDto
{
    /**
     * @OA\Property()
     */
    public ConnectionInviteResultDto $item;
}
