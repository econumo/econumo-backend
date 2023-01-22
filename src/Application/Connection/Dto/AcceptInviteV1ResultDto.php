<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class AcceptInviteV1ResultDto
{
    /**
     * @var ConnectionResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
