<?php

declare(strict_types=1);

namespace _CG_APPROOT_\Application\_CG_MODULE_\_CG_SUBJECT_\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"result"}
 * )
 */
class _CG_ACTION__CG_SUBJECT__CG_VERSION_ResultDto
{
    /**
     * Id
     * @SWG\Property(example="This is result")
     */
    public string $result;
}
