<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use App\Application\Payee\Dto\PayeeResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class CreatePayeeV1ResultDto
{
    /**
     * Payee
     * @SWG\Property()
     */
    public PayeeResultDto $item;
}
