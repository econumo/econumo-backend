<?php
declare(strict_types=1);

namespace App\Application\__CG_API_SUBJECT_CC__\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class __CG_API_ACTION_CC__DisplayDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="123")
     */
    public $id;

    /**
     * Name
     * @var string
     * @SWG\Property(example="subject")
     */
    public $name;
}
