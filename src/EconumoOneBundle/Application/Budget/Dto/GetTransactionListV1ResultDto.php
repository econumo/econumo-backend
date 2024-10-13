<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetTransactionListV1ResultDto
{
    /**
     * @var BudgetTransactionResultDto[]
     * @OA\Property()
     */
    public array $items;
}
