<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class ArchivePayeeV1RequestDto
{
    /**
     * @SWG\Property(example="701ee173-7c7e-4f92-8af7-a27839c663e0")
     */
    public string $id;
}
