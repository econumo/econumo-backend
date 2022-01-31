<?php

declare(strict_types=1);

namespace App\Application\Connection\Dto;

use App\Application\Account\Dto\AccountResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accounts"}
 * )
 */
class DeleteConnectionV1ResultDto
{
    /**
     * @var AccountResultDto[]
     * @SWG\Property()
     */
    public array $accounts = [];
}
