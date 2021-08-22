<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction\Dto;

use App\Application\Transaction\Collection\Dto\TransactionResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accountBalance"}
 * )
 */
class DeleteTransactionV1ResultDto
{
    /**
     * Account balance
     * @SWG\Property(example="43.16")
     */
    public float $accountBalance;

    /**
     * Account recipient balance
     * @SWG\Property(example="215.43")
     */
    public ?float $accountRecipientBalance = null;

    public TransactionResultDto $transaction;
}
