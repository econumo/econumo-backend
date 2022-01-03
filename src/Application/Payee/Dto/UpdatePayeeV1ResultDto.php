<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class UpdatePayeeV1ResultDto
{
    /**
     * Payee
     * @SWG\Property()
     */
    public PayeeResultDto $item;
}
