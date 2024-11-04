<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"value"}
 * )
 */
class UpdateReportPeriodV1RequestDto
{
    /**
     * @OA\Property(example="monthly")
     */
    public string $value;
}
