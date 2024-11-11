<?php
declare(strict_types=1);

namespace App\EconumoBundle\Application\Budget\Dto;

use App\EconumoBundle\Application\Budget\Dto\BudgetStructureChildElementResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "type", "name", "icon", "currencyId", "isArchived", "folderId", "position", "budget", "available", "spent", "budgetSpent", "children", "ownerUserId"}
 * )
 */
class BudgetStructureParentElementResultDto
{
    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $id;

    /**
     * @OA\Property(example="0")
     */
    public int $type;

    /**
     * @OA\Property(example="Car")
     */
    public string $name;

    /**
     * @OA\Property(example="Wallet")
     */
    public string $icon;

    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $currencyId;

    /**
     * @OA\Property(example="0")
     */
    public int $isArchived;

    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public ?string $folderId = null;

    /**
     * @OA\Property(example="0")
     */
    public int $position;

    /**
     * @OA\Property(example="150.04")
     */
    public float $budgeted;

    /**
     * @OA\Property(example="150.0")
     */
    public float $available;

    /**
     * @OA\Property(example="10.0")
     */
    public float $spent;

    /**
     * @OA\Property(example="10.0")
     */
    public float $budgetSpent;

    /**
     * @var BudgetStructureChildElementResultDto[]
     * @OA\Property()
     */
    public array $children;

    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public ?string $ownerUserId;
}