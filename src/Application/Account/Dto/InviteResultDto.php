<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accountId", "code", "role"}
 * )
 */
class InviteResultDto
{
    /**
     * @SWG\Property(example="5a628eda-aa93-4d0b-b74e-a5a15d7468a3")
     */
    public string $accountId;

    /**
     * @SWG\Property(example="12345")
     */
    public ?string $code = null;

    /**
     * @SWG\Property(example="admin")
     */
    public string $role;

    /**
     * @SWG\Property(example="recipient@econumo.dev")
     */
    public string $recipientUsername;

    /**
     * @SWG\Property(example="John Do")
     */
    public string $recipientName;
}