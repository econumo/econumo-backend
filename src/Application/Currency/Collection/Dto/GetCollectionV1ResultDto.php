<?php

declare(strict_types=1);

namespace App\Application\Currency\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCollectionV1ResultDto
{
    /**
     * @var CurrencyResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
