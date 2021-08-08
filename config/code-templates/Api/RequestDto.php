<?php

declare(strict_types=1);

namespace _CG_APPROOT_\Application\_CG_MODULE_\_CG_SUBJECT_\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class _CG_ACTION__CG_SUBJECT__CG_VERSION_RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;
}
