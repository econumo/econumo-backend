<?php

declare(strict_types=1);

namespace App\Application\Tag\Tag\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name"}
 * )
 */
class CreateTagV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;

    /**
     * @SWG\Property(example="#shopping")
     */
    public string $name;
}
