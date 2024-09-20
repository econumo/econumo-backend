<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class GetStructureV1ResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetResultDto $item;
}
