<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"invite"}
 * )
 */
class GenerateInviteV1ResultDto
{
    /**
     * Id
     * @SWG\Property()
     */
    public InviteResultDto $invite;
}
