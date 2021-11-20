<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class GetCollectionV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;
}
