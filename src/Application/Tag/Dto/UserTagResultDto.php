<?php
declare(strict_types=1);

namespace App\Application\Tag\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "position", "isArchived", "createdAt", "updatedAt"}
 * )
 */
class UserTagResultDto extends TagResultDto
{
    /**
     * Position
     * @var int
     * @OA\Property(example="0")
     */
    public int $position;
}
