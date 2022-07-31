<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetAccountListV1ResultDto
{
    /**
     * @var AccountResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
