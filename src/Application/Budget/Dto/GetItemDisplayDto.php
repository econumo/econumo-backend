<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"period", "values"}
 * )
 */
class GetItemDisplayDto
{
    /**
     * @var GetItemPeriodDisplayDto[]
     * @SWG\Property()
     */
    public $period;

    /**
     * @var GetItemValueDisplayDto[]
     * @SWG\Property()
     */
    public $values;
}
