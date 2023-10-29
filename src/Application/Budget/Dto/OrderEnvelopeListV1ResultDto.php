<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class OrderEnvelopeListV1ResultDto
{
    /**
     * @var PlanDataEnvelopeResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
