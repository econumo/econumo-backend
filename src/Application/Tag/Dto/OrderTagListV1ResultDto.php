<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class OrderTagListV1ResultDto
{
    /**
     * @var TagResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
