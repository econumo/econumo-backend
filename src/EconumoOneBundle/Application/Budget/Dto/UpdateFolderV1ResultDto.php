<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={}
 * )
 */
class UpdateFolderV1ResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetFolderResultDto $item;
}
