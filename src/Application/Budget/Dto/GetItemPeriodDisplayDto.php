<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "monthNumber", "position"}
 * )
 */
class GetItemPeriodDisplayDto
{
    /**
     * Key (year and month)
     * @var string
     * @SWG\Property(example="2020-02")
     */
    public $id;

    /**
     * Month number
     * @var string
     * @SWG\Property(example="02")
     */
    public $monthNumber;

    /**
     * Position
     * @var int
     * @SWG\Property(example="1")
     */
    public $position;
}
