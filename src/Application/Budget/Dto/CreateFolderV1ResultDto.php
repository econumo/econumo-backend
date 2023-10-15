<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreateFolderV1ResultDto
{
    /**
     * Id
     * @OA\Property()
     */
    public PlanFolderResultDto $item;
}
