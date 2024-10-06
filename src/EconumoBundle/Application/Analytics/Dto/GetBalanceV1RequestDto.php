<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Analytics\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"from", "to"},
 * )
 */
class GetBalanceV1RequestDto
{
    /**
     * @OA\Property(example="2020-01-01")
     */
    public string $from;

    /**
     * @OA\Property(example="2020-06-01")
     */
    public string $to;
}
