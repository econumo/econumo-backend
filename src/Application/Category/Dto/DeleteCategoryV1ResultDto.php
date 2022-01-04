<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"result"}
 * )
 */
class DeleteCategoryV1ResultDto
{
    /**
     * Id
     * @SWG\Property(example="This is result")
     */
    public string $result;
}
