<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class AcceptAccessV1ResultDto
{
    /**
     * Id
     * @OA\Property()
     */
    public PlanResultDto $item;
}
