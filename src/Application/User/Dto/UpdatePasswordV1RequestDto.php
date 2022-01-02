<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"oldPassword", "newPassword"}
 * )
 */
class UpdatePasswordV1RequestDto
{
    /**
     * @SWG\Property(example="pass")
     */
    public string $oldPassword;

    /**
     * @SWG\Property(example="new_pass")
     */
    public string $newPassword;
}
