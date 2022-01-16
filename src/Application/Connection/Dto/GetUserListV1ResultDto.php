<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use App\Domain\Entity\User;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetUserListV1ResultDto
{
    /**
     * @var User[]
     * @SWG\Property()
     */
    public array $items;
}
