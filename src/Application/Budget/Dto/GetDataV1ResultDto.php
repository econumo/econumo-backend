<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetDataV1ResultDto
{
    /**
     * @var PlanDataResultDto[]
     * @OA\Property()
     */
    public array $items;
}
