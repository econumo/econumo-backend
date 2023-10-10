<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use App\Application\Category\Dto\UserCategoryResultDto;
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
    public UserCategoryResultDto $item;
}
