<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "startedAt", "sharedAccess"}
 * )
 */
class BudgeFiltersResultDto
{
    /**
     * @OA\Property(example="2022-01-01 00:00:00")
     */
    public string $periodStart;

    /**
     * @OA\Property(example="2022-02-01 00:00:00")
     */
    public string $periodEnd;

    /**
     * Excluded account IDs
     * @var string[]
     * @OA\Property()
     */
    public array $excludedAccountIds;

    /**
     * Budget Currencies IDs
     * @var string[]
     * @OA\Property()
     */
    public array $currencyIds;
}