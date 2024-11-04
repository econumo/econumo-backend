<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Dto;

use App\EconumoOneBundle\Application\Payee\Dto\PayeeResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetPayeeListV1ResultDto
{
    /**
     * @var PayeeResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
