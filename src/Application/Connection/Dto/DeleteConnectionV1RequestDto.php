<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class DeleteConnectionV1RequestDto
{
    /**
     * User id
     * @SWG\Property(example="77be9577-147b-4f05-9aa7-91d9b159de5b")
     */
    public string $id;
}
