<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreateAccountV1ResultDto
{
    /**
     * @OA\Property()
     */
    public AccountResultDto $item;
}
