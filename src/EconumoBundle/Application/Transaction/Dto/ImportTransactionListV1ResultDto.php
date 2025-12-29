<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"result"}
 * )
 */
class ImportTransactionListV1ResultDto
{
    /**
     * Id
     * @OA\Property(example="This is result")
     */
    public string $result;
}
