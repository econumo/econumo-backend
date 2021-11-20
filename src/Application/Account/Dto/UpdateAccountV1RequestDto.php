<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "balance"}
 * )
 */
class UpdateAccountV1RequestDto
{
    /**
     * @SWG\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $id;

    /**
     * @SWG\Property(example="Cash")
     */
    public string $name;

    /**
     * @SWG\Property(example="21007.64")
     */
    public float $balance = 0.0;

    /**
     * @SWG\Property(example="wallet")
     */
    public string $icon = '';

    /**
     * @SWG\Property(example="2020-01-01 23:59:59")
     */
    public string $updatedAt;

    /**
     * @SWG\Property(example="correction")
     */
    public string $comment = '';
}
