<?php
declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetListDisplayDto
{
    /**
     * @var CategoryDisplayDto[]
     * @SWG\Property()
     */
    public $items = [];
}
