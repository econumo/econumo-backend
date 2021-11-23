<?php

declare(strict_types=1);

namespace App\Application\Transaction\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class GetTransactionListV1RequestDto
{
    /**
     * @SWG\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public ?string $accountId = null;
}
