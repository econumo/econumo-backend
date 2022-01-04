<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "mode"}
 * )
 */
class DeleteCategoryV1RequestDto
{
    public const MODE_DELETE = 'delete';
    public const MODE_REPLACE = 'replace';

    /**
     * @SWG\Property(example="95587d1d-2c39-4efc-98f3-23c755da44a4")
     */
    public string $id;

    /**
     * @SWG\Property(example="delete or replace")
     */
    public string $mode;

    /**
     * @SWG\Property(example="ed547399-a380-43c9-b164-d8e435e043c9")
     */
    public ?string $replaceId = null;
}
