<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class UpdateFolderV1ResultDto
{
    /**
     * @SWG\Property()
     */
    public FolderResultDto $item;
}
