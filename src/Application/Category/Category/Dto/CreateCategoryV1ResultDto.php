<?php

declare(strict_types=1);

namespace App\Application\Category\Category\Dto;

use App\Application\Category\Collection\Dto\CategoryResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"category"}
 * )
 */
class CreateCategoryV1ResultDto
{
    /**
     * Category
     * @SWG\Property()
     */
    public CategoryResultDto $category;
}
