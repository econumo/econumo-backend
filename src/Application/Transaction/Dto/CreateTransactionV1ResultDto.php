<?php

declare(strict_types=1);

namespace App\Application\Transaction\Dto;

use App\Application\Account\Dto\AccountResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item", "accounts"}
 * )
 */
class CreateTransactionV1ResultDto
{
    /**
     * Transaction
     * @SWG\Property()
     */
    public TransactionResultDto $item;

    /**
     * @var AccountResultDto[]
     * @SWG\Property()
     */
    public array $accounts = [];
}
