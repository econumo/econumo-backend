<?php
declare(strict_types=1);

namespace App\Application\Transaction\Dto;

use App\Application\Category\Dto\CategoryResultDto;
use App\Application\Payee\Dto\PayeeResultDto;
use App\Application\Tag\Dto\TagResultDto;
use App\Application\User\Dto\UserResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "author", "type", "accountId", "amount", "description", "date"}
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
     * Author of transaction
     * @var UserResultDto
     * @SWG\Property()
     */
    public UserResultDto $author;

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
     * Category
     * @SWG\Property()
     */
    public ?CategoryResultDto $category = null;

    /**
     * Description
     * @SWG\Property(example="bananas")
     */
    public string $description;

    /**
     * Payee
     * @SWG\Property()
     */
    public ?PayeeResultDto $payee = null;

    /**
     * Tag
     * @SWG\Property()
     */
    public ?TagResultDto $tag = null;

    /**
     * Transaction date
     * @SWG\Property(example="2021-08-01 10:00:00")
     */
    public string $date;
}
