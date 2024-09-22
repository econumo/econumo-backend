<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "periodStart", "periodEnd", "currencyBalances", "averageCurrencyRates", "entityAmounts"}
 * )
 */
class GetDataV1ResultDto
{
    /**
     * @OA\Property(example="05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9")
     */
    public string $id;

    /**
     * @OA\Property(example="2022-02-01 00:00:00")
     */
    public string $periodStart;

    /**
     * @OA\Property(example="2022-03-01 00:00:00")
     */
    public string $periodEnd;

    /**
     * @var BudgetCurrencyBalanceDto[]
     * @OA\Property()
     */
    public array $currencyBalances;

    /**
     * @var AverageCurrencyRateDto[]
     * @OA\Property()
     */
    public array $averageCurrencyRates;

    /**
     * @var BudgetEntityAmountDto[]
     * @OA\Property()
     */
    public array $entityAmounts;
}
