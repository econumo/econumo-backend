<?php

declare(strict_types=1);

namespace App\Application\Account\Account\Dto;

use App\Application\Account\Collection\Dto\AccountResultDto;
use App\Application\Transaction\Collection\Dto\TransactionResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class UpdateAccountV1ResultDto
{
    /**
     * @SWG\Property()
     */
    public AccountResultDto $item;

    /**
     * @SWG\Property()
     */
    public ?TransactionResultDto $transaction = null;
}
