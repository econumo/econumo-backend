<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Transaction\Dto;

use App\EconumoOneBundle\Application\Account\Dto\AccountResultDto;
use App\EconumoOneBundle\Application\Transaction\Dto\TransactionResultDto;
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
