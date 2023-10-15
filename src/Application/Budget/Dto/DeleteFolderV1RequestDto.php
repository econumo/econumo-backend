<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id"}
 * )
 */
class DeleteFolderV1RequestDto
{
    /**
     * @OA\Property(example="62ccc225-b141-42a4-8063-825c8b72d135")
     */
    public string $id;
}
