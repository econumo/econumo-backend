<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Transaction\Dto;

use App\EconumoOneBundle\Application\Transaction\Dto\TransactionResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetTransactionListV1ResultDto
{
    /**
     * @var TransactionResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
