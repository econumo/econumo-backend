<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class CreateTransactionV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;

    /**
     * @SWG\Property(example="expense")
     */
    public string $type;

    /**
     * @SWG\Property(example="1234.4")
     */
    public float $amount;

    /**
     * @SWG\Property(example="1234.4")
     */
    public ?float $amountRecipient = null;

    /**
     * @SWG\Property(example="")
     */
    public string $accountId;

    /**
     * @SWG\Property(example="")
     */
    public ?string $accountRecipientId = null;

    /**
     * @SWG\Property(example="")
     */
    public ?string $categoryId = null;

    /**
     * @SWG\Property(example="2021-07-22 00:22:00")
     */
    public string $date;

    /**
     * @SWG\Property(example="")
     */
    public ?string $description = null;

    /**
     * @SWG\Property(example="")
     */
    public ?string $payeeId = null;

    /**
     * @SWG\Property(example="")
     */
    public ?string $tagId = null;
}
