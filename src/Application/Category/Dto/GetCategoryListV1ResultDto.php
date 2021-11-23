<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCategoryListV1ResultDto
{
    /**
     * @var CategoryResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
