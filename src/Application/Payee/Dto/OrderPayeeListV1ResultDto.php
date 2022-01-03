<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class OrderPayeeListV1ResultDto
{
    /**
     * @var PayeeResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
