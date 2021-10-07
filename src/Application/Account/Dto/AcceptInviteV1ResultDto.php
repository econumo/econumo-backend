<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\Account\Collection\Dto\AccountResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"account"}
 * )
 */
class AcceptInviteV1ResultDto
{
    /**
     * @SWG\Property()
     */
    public AccountResultDto $account;
}
