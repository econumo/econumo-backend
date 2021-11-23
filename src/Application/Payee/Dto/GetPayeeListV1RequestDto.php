<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class GetPayeeListV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;
}
