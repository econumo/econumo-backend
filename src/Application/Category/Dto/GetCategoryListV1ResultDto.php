<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetCategoryListV1ResultDto
{
    /**
     * @var UserCategoryResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
