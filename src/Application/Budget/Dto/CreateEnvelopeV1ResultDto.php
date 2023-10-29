<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreateEnvelopeV1ResultDto
{
    /**
     * @OA\Property()
     */
    public EnvelopeResultDto $item;
}
