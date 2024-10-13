<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Dto;

use App\EconumoOneBundle\Application\Account\Dto\AccountResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class OrderAccountListV1ResultDto
{
    /**
     * @var AccountResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
