<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\Account\Dto\AccountResultDto;
use App\Application\Transaction\Dto\TransactionResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class UpdateAccountV1ResultDto
{
    /**
     * @OA\Property()
     */
    public AccountResultDto $item;

    /**
     * @OA\Property()
     */
    public ?TransactionResultDto $transaction = null;
}
