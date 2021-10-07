<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accepted", "waiting"}
 * )
 */
class GetInviteV1ResultDto
{
    /**
     * @var InviteResultDto[]
     * @SWG\Property()
     */
    public array $accepted = [];

    /**
     * @var InviteResultDto[]
     * @SWG\Property()
     */
    public array $waiting = [];
}
