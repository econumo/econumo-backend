<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accountId", "userId"}
 * )
 */
class SetAccountAccessV1RequestDto
{
    /**
     * @SWG\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $accountId;

    /**
     * @SWG\Property(example="aff21334-96f0-4fb1-84d8-0223d0280954")
     */
    public string $userId;

    /**
     * @SWG\Property(example="admin")
     */
    public string $role;
}
