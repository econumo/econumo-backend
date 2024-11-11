<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Analytics\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"date", "amount"}
 * )
 */
class BalanceResultDto
{
    /**
     * Balance for a date
     * @OA\Property(example="2021-01-01")
     */
    public string $date;

    /**
     * Balance amount
     * @OA\Property(example="0.00")
     */
    public string $amount;
}
