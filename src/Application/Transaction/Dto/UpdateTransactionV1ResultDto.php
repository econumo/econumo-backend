<?php

declare(strict_types=1);

namespace App\Application\Transaction\Dto;

use App\Application\Account\Dto\AccountResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item", "accounts"}
 * )
 */
class UpdateTransactionV1ResultDto
{
    /**
     * Transaction
     * @OA\Property()
     */
    public TransactionResultDto $item;

    /**
     * @var AccountResultDto[]
     * @OA\Property()
     */
    public array $accounts = [];
}
