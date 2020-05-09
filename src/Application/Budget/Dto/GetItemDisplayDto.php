<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"budget", "period", "values"}
 * )
 */
class GetItemDisplayDto
{
    /**
     * @var GetListItemDisplayDto
     * @SWG\Property()
     */
    public $budget;

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
