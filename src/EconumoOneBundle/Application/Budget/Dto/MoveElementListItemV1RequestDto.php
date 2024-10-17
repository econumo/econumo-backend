<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "type", "position", "folderId"}
 * )
 */
class MoveElementListItemV1RequestDto
{
    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $id;

    /**
     * @OA\Property(example="envelope")
     */
    public int $type;

    /**
     * @OA\Property(example=0)
     */
    public int $position;

    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public ?string $folderId = null;
}
