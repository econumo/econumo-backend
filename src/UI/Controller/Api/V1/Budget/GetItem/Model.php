<?php
declare(strict_types=1);

namespace App\UI\Controller\Api\V1\Budget\GetItem;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "fromDate", "toDate"}
 * )
 */
class Model
{
    /**
     * Budget ID
     *
     * @var string
     */
    public $id;

    /**
     * From date
     *
     * @var string
     */
    public $fromDate;

    /**
     * To date
     *
     * @var string
     */
    public $toDate;
}
