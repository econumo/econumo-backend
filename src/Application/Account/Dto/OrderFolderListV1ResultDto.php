<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class OrderFolderListV1ResultDto
{
    /**
     * @var FolderResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
