<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Currency\Dto;

use App\EconumoOneBundle\Application\Currency\Dto\CurrencyRateResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetCurrencyRateListV1ResultDto
{
    /**
     * @var CurrencyRateResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
