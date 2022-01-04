<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "icon", "isArchived"}
 * )
 */
class UpdateCategoryV1RequestDto
{
    /**
     * @SWG\Property(example="3a2c32a4-45ec-4cb0-9794-a6bef87ba9a4")
     */
    public string $id;

    /**
     * @SWG\Property(example="Food")
     */
    public string $name;

    /**
     * @SWG\Property(example="local_offer")
     */
    public string $icon;

    /**
     * @SWG\Property(example="0")
     */
    public int $isArchived;
}
