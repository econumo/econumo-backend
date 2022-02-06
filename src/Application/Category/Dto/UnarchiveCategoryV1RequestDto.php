<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class UnarchiveCategoryV1RequestDto
{
    /**
     * @SWG\Property(example="95587d1d-2c39-4efc-98f3-23c755da44a4")
     */
    public string $id;
}
