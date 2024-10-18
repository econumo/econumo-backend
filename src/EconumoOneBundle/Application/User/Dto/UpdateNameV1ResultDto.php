<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\User\Dto;

use App\EconumoOneBundle\Application\User\Dto\CurrentUserResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"user"}
 * )
 */
class UpdateNameV1ResultDto
{
    public CurrentUserResultDto $user;
}