<?php
declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Application\User\Dto\UserResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"user", "role"}
 * )
 */
class SharedAccessItemResultDto
{
    /**
     * User
     * @var UserResultDto
     * @SWG\Property()
     */
    public UserResultDto $user;

    /**
     * User role
     * @var string
     * @SWG\Property(example="admin")
     */
    public string $role;
}
