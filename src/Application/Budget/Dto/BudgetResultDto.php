<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"meta", "filters", "balances", "structure"}
 * )
 */
class BudgetResultDto
{
    /**
     * @OA\Property()
     */
    public BudgetMetaResultDto $meta;

    /**
     * @OA\Property()
     */
    public BudgeFiltersResultDto $filters;

    /**
     * @var BudgetCurrencyBalanceResultDto[]
     * @OA\Property()
     */
    public array $balances;

    /**
     * @OA\Property()
     */
    public BudgetStructureResultDto $structure;
}