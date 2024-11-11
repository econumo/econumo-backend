<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Analytics\Dto;

use App\EconumoBundle\Application\Analytics\Dto\BalanceResultDto;
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
