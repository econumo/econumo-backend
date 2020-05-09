<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class GetListItemDisplayDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public $id;

    /**
     * Name
     * @var string
     * @SWG\Property(example="Taxes")
     */
    public $name;

    /**
     * Position
     * @var int
     * @SWG\Property(example="0")
     */
    public $position;

    /**
     * Currency Id
     * @var string
     * @SWG\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public $currencyId;
}
