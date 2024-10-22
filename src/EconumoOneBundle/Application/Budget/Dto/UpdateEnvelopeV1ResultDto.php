<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class UpdateEnvelopeV1ResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetStructureParentElementResultDto $item;
}
