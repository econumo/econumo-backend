<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class ExcludeAccountV1ResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetMetaResultDto $item;
}
