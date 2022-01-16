<?php

declare(strict_types=1);


namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "role"}
 * )
 */
class AccountAccessResultDto
{
    /**
     * Account id
     * @var string
     * @SWG\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $id;

    /**
     * @var string
     * @SWG\Property(example="77be9577-147b-4f05-9aa7-91d9b159de5b")
     */
    public string $ownerUserId;

    /**
     * User role
     * @var string
     * @SWG\Property(example="admin")
     */
    public string $role;
}
