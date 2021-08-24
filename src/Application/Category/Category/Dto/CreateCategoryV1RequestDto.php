<?php

declare(strict_types=1);

namespace App\Application\Category\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name"}
 * )
 */
class CreateCategoryV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;

    /**
     * @SWG\Property(example="Food")
     */
    public string $name;

    /**
     * @SWG\Property(example="expense")
     */
    public string $type;
}
