<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "isArchived"}
 * )
 */
class UpdateTagV1RequestDto
{
    /**
     * @SWG\Property(example="4b53d029-c1ed-46ad-8d86-1049542f4a7e")
     */
    public string $id;

    /**
     * @SWG\Property(example="#work")
     */
    public string $name;

    /**
     * @SWG\Property(example="0")
     */
    public int $isArchived;
}
