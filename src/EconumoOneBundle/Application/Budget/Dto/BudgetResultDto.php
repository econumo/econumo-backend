<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use App\EconumoOneBundle\Application\Budget\Dto\BudgeFiltersResultDto;
use App\EconumoOneBundle\Application\Budget\Dto\BudgetCurrencyBalanceResultDto;
use App\EconumoOneBundle\Application\Budget\Dto\BudgetMetaResultDto;
use App\EconumoOneBundle\Application\Budget\Dto\BudgetStructureResultDto;
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