<?php
declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "level", "position", "isIncome", "permissions"}
 * )
 */
class CategoryDisplayDto
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
     * Level
     * @var int
     * @SWG\Property(example="0")
     */
    public $level;

    /**
     * Position
     * @var int
     * @SWG\Property(example="0")
     */
    public $position;

    /**
     * Is income?
     * @var bool
     * @SWG\Property(example="true")
     */
    public $isIncome;

    /**
     * @var CategoryPermissionsDisplayDto
     * @SWG\Property()
     */
    public $permissions;
}
