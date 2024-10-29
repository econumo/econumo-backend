<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GrantAccessV1ResultDto
{
    /**
     * @var BudgetMetaResultDto[]
     * @OA\Property()
     */
    public array $items;
}