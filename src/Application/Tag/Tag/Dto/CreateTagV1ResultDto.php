<?php

declare(strict_types=1);

namespace App\Application\Tag\Tag\Dto;

use App\Application\Tag\Collection\Dto\TagResultDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"item"}
 * )
 */
class CreateTagV1ResultDto
{
    /**
     * Tag
     * @SWG\Property()
     */
    public TagResultDto $item;
}
