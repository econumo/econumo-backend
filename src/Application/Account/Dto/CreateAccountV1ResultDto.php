<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class CreateAccountV1ResultDto
{
    /**
     * @SWG\Property()
     */
    public AccountResultDto $item;
}
