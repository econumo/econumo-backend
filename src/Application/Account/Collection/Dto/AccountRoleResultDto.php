<?php
declare(strict_types=1);

namespace App\Application\Account\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"userId", "role"}
 * )
 */
class AccountRoleResultDto
{
    /**
     * User id
     * @var string
     * @SWG\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $userId;

    /**
     * User role
     * @var string
     * @SWG\Property(example="admin")
     */
    public string $role;
}