<?php

declare(strict_types=1);

namespace App\Application\Account\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"accounts"}
 * )
 */
class ReorderCollectionV1RequestDto
{
    /**
     * @var AccountRequestDto[]
     * @SWG\Property()
     */
    public array $accounts;
}
