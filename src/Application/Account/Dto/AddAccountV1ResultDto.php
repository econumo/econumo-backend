<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\Account\Dto\AccountResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class AddAccountV1ResultDto
{
    /**
     * @SWG\Property()
     */
    public AccountResultDto $item;
}
