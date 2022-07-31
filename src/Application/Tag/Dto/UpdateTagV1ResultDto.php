<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class UpdateTagV1ResultDto
{
    /**
     * Tag
     * @OA\Property()
     */
    public TagResultDto $item;
}
