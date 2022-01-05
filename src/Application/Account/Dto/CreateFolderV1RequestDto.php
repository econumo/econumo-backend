<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"name"}
 * )
 */
class CreateFolderV1RequestDto
{
    /**
     * @SWG\Property(example="Savings")
     */
    public string $name;
}
