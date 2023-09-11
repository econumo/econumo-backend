<?php

declare(strict_types=1);

namespace App\Application\Analytics\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetBalanceV1ResultDto
{
    /**
     * @var BalanceResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
