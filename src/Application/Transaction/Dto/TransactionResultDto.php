<?php
declare(strict_types=1);

namespace App\Application\Transaction\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "authorId", "type", "accountId", "amount", "categoryId", "description", "date"}
 * )
 */
class TransactionResultDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public string $id;

    /**
     * User author id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $authorId;

    /**
     * User author name
     * @SWG\Property(example="John")
     */
    public string $authorName;

    /**
     * Transaction type
     * @SWG\Property(example="expense")
     */
    public string $type;

    /**
     * Account id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $accountId;

    /**
     * Account recipient id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public ?string $accountRecipientId;

    /**
     * Amount
     * @SWG\Property(example="100.5")
     */
    public float $amount;

    /**
     * Amount recipient
     * @SWG\Property(example="100.5")
     */
    public ?float $amountRecipient;

    /**
     * Category id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public ?string $categoryId;

    /**
     * Category name
     * @SWG\Property(example="Food")
     */
    public string $categoryName;

    /**
     * Description
     * @SWG\Property(example="bananas")
     */
    public string $description;

    /**
     * Payee id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public ?string $payeeId;

    /**
     * Payee name
     * @SWG\Property(example="Amazon")
     */
    public string $payeeName = '';

    /**
     * Tag id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public ?string $tagId;

    /**
     * Tag name
     * @SWG\Property(example="#travel")
     */
    public string $tagName;

    /**
     * Transaction date
     * @SWG\Property(example="2021-08-01 10:00:00")
     */
    public string $date;

    /**
     * Transaction day
     * @SWG\Property(example="2021-08-01")
     */
    public string $day;

    /**
     * Transaction time
     * @SWG\Property(example="10:00")
     */
    public string $time;
}
