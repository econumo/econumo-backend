<?php

declare(strict_types=1);

namespace App\Application\Tag\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"result"}
 * )
 */
class ArchiveTagV1ResultDto
{
    /**
     * Id
     * @SWG\Property(example="This is result")
     */
    public string $result;
}
