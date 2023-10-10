<?php
declare(strict_types=1);

namespace App\Application\Category\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "position", "type", "icon", "isArchived", "createdAt", "updatedAt"}
 * )
 */
class UserCategoryResultDto extends CategoryResultDto
{
    /**
     * Position
     * @var int
     * @OA\Property(example="0")
     */
    public int $position;
}
