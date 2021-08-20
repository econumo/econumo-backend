<?php

declare(strict_types=1);

namespace App\Application\Tag\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCollectionV1ResultDto
{
    /**
     * @var TagResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
