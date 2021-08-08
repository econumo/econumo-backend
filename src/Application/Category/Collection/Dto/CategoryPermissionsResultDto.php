<?php
declare(strict_types=1);

namespace App\Application\Category\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"canEdit"}
 * )
 */
class CategoryPermissionsResultDto
{
    /**
     * Can edit?
     * @var bool
     * @SWG\Property(example="true")
     */
    public $canEdit;
}
