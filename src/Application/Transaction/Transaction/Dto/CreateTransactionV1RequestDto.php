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
    public ?float $amountRecipient;

    /**
     * @SWG\Property(example="")
     */
    public string $accountId;

    /**
     * @SWG\Property(example="")
     */
    public ?string $accountRecipientId;

    /**
     * @SWG\Property(example="")
     */
    public string $categoryId;

    /**
     * @SWG\Property(example="")
     */
    public string $description;

    /**
     * @SWG\Property(example="")
     */
    public ?string $payeeId;

    /**
     * @SWG\Property(example="")
     */
    public ?string $tagId;

    /**
     * @SWG\Property(example="")
     */
    public string $date;
}
