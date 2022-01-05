<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name"}
 * )
 */
class UpdateFolderV1RequestDto
{
    /**
     * @SWG\Property(example="1ad16d32-36af-496e-9867-3919436b8d86")
     */
    public string $id;

    /**
     * @SWG\Property(example="Savings")
     */
    public string $name;
}
