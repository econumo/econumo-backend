<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class GetTagListV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;
}
