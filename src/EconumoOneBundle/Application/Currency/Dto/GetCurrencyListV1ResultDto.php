<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency\Dto;

use App\EconumoOneBundle\Application\Currency\Dto\CurrencyResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetCurrencyListV1ResultDto
{
    /**
     * @var CurrencyResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
