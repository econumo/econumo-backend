<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use App\Application\User\Dto\UserResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetUserListV1ResultDto
{
    /**
     * @var UserResultDto[]
     * @SWG\Property()
     */
    public array $items;
}
