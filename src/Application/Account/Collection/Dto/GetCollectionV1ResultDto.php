<?php

declare(strict_types=1);

namespace App\Application\Account\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCollectionV1ResultDto
{
    /**
     * @var AccountResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
