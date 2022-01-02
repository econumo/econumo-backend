<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class UpdateTagV1ResultDto
{
    /**
     * Tag
     * @SWG\Property()
     */
    public TagResultDto $item;
}
