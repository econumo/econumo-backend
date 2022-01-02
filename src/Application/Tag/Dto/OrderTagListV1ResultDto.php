<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class OrderTagListV1ResultDto
{
    /**
     * @var TagResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
