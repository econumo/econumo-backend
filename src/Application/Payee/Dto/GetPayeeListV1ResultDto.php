<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetPayeeListV1ResultDto
{
    /**
     * @var PayeeResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
