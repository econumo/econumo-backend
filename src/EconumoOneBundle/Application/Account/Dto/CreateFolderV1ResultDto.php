<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Dto;

use App\EconumoOneBundle\Application\Account\Dto\FolderResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreateFolderV1ResultDto
{
    /**
     * @OA\Property()
     */
    public FolderResultDto $item;
}
