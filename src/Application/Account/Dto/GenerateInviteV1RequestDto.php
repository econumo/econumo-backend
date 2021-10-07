<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accountId", "recipientId", "role"}
 * )
 */
class GenerateInviteV1RequestDto
{
    /**
     * @SWG\Property(example="5a628eda-aa93-4d0b-b74e-a5a15d7468a3")
     */
    public string $accountId;

    /**
     * @SWG\Property(example="dmitry@econumo.local")
     */
    public string $recipientUsername;

    /**
     * @SWG\Property(example="admin")
     */
    public string $role;
}
