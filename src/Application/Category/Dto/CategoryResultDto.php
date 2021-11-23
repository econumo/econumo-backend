<?php
declare(strict_types=1);

namespace App\Application\Category\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "ownerUserId", "name", "position", "type"}
 * )
 */
class CategoryResultDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public string $id;

    /**
     * Owner user id
     * @SWG\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $ownerUserId;

    /**
     * Name
     * @var string
     * @SWG\Property(example="Taxes")
     */
    public string $name;

    /**
     * Position
     * @var int
     * @SWG\Property(example="0")
     */
    public int $position;

    /**
     * Category type
     * @var string
     * @SWG\Property(example="expense")
     */
    public string $type;
}
