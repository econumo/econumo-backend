<?php

declare(strict_types=1);

namespace App\Application\Transaction\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetTransactionListV1ResultDto
{
    /**
     * @var TransactionResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
