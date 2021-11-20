<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\Account\Dto\AccountResultDto;
use App\Application\Transaction\Dto\TransactionResultDto;
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
