<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"entityId", "entityType", "position", "currencyId", "folderId"}
 * )
 */
class BudgetEntityOptionResultDto
{
    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $entityId;

    /**
     * @OA\Property(example="envelope")
     */
    public string $entityType;

    /**
     * @OA\Property(example="0")
     */
    public int $position;

    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public ?string $currencyId = null;

    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public ?string $folderId = null;
}