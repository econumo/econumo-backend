<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetMetaResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class UpdateBudgetV1ResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetMetaResultDto $item;
}
