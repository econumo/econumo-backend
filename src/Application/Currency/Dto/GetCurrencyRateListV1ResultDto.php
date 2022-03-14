<?php

declare(strict_types=1);

namespace App\Application\Currency\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCurrencyRateListV1ResultDto
{
    /**
     * @var CurrencyRateResultDto[]
     * @SWG\Property()
     */
    public array $items;
}
