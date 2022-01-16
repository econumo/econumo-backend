<?php

declare(strict_types=1);


namespace App\Application\Connection\Dto;

use App\Application\User\Dto\UserResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"user", "accountAccess"}
 * )
 */
class ConnectionResultDto
{
    /**
     * @var UserResultDto
     * @SWG\Property()
     */
    public UserResultDto $user;

    /**
     * @var AccountAccessResultDto[]
     * @SWG\Property()
     */
    public array $sharedAccounts;
}
