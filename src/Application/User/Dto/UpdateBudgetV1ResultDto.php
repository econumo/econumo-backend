<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"user"}
 * )
 */
class UpdateBudgetV1ResultDto
{
    /**
     * @OA\Property()
     */
    public CurrentUserResultDto $user;
}
