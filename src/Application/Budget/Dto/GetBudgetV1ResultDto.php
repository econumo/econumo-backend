<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"result"}
 * )
 */
class GetBudgetV1ResultDto
{
    /**
     * Id
     * @OA\Property(example="This is result")
     */
    public string $result;
}
