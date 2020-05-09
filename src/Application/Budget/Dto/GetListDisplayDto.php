<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetListDisplayDto
{
    /**
     * @var GetListItemDisplayDto[]
     * @SWG\Property()
     */
    public $items = [];
}
