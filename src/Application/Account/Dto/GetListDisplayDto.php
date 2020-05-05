<?php
declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetListDisplayDto
{
    /**
     * @var AccountDisplayDto[]
     * @SWG\Property()
     */
    public $items = [];
}
