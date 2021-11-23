<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetAccountListV1ResultDto
{
    /**
     * @var AccountResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
