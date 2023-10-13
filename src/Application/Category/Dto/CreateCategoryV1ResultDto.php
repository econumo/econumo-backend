<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use App\Application\Category\Dto\CategoryResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreateCategoryV1ResultDto
{
    /**
     * Category
     * @OA\Property()
     */
    public CategoryResultDto $item;
}
