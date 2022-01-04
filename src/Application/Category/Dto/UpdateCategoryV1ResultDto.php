<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class UpdateCategoryV1ResultDto
{
    /**
     * Tag
     * @SWG\Property()
     */
    public CategoryResultDto $item;
}
