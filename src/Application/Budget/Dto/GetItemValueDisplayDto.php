<?php
declare(strict_types=1);

namespace App\Application\Budget\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id"}
 * )
 */
class GetItemValueDisplayDto
{
    /**
     * Key (year and month)
     * @var string
     * @SWG\Property(example="2020-02")
     */
    public $periodId;

    /**
     * Category UUID
     * @var string
     * @SWG\Property(example="73a57300-4563-48d6-b0c3-79ef7b7fbc51")
     */
    public $categoryId;

    /**
     * Expected amount
     * @var string
     * @SWG\Property(example="271.03")
     */
    public $expectedValue;

    /**
     * Actual amount
     * @var string
     * @SWG\Property(example="50.13")
     */
    public $actualValue;
}
