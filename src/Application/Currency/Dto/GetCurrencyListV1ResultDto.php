<?php

declare(strict_types=1);

namespace App\Application\Currency\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCurrencyListV1ResultDto
{
    /**
     * @var CurrencyResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
