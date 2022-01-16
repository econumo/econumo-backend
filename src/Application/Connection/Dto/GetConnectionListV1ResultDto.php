<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetConnectionListV1ResultDto
{
    /**
     * @var ConnectionResultDto[]
     * @SWG\Property()
     */
    public array $items;
}
