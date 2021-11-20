<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "type"}
 * )
 */
class CreateCategoryV1RequestDto
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
     * @SWG\Property(example="expense")
     */
    public string $type;

    /**
     * @SWG\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public ?string $accountId = null;
}
