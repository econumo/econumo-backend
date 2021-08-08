<?php

declare(strict_types=1);

namespace App\Application\Category\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"result"}
 * )
 */
class GetCollectionV1ResultDto
{
    /**
     * @var CategoryResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
