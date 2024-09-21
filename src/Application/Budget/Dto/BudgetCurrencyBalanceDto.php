<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"currencyId", "startBalance", "endBalance", "income", "expenses", "exchanges", "holdings"}
 * )
 */
class BudgetCurrencyBalanceDto
{
    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $currencyId;

    /**
     * @OA\Property(example="12.05")
     */
    public ?float $startBalance;

    /**
     * @OA\Property(example="12.05")
     */
    public ?float $endBalance;

    /**
     * @OA\Property(example="12.05")
     */
    public ?float $income;

    /**
     * @OA\Property(example="12.05")
     */
    public ?float $expenses;

    /**
     * @OA\Property(example="12.05")
     */
    public ?float $exchanges;

    /**
     * @OA\Property(example="12.05")
     */
    public ?float $holdings;
}