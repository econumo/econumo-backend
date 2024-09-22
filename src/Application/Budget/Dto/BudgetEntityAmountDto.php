<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "type", "budget", "available", "spent"}
 * )
 */
class BudgetEntityAmountDto
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
     * @OA\Property(example="100.0")
     */
    public float $budget;

    /**
     * @OA\Property(example="500.0")
     */
    public float $available;

    /**
     * @var BudgetEntityAmountSpentDto[]
     * @OA\Property()
     */
    public array $spent = [];
}