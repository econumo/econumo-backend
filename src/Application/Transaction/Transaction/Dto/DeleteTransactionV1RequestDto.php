<?php

declare(strict_types=1);

namespace App\Application\Transaction\Transaction\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class DeleteTransactionV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;
}
