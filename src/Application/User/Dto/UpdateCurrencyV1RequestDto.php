<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"currency"}
 * )
 */
class UpdateCurrencyV1RequestDto
{
    /**
     * @SWG\Property(example="USD")
     */
    public string $currency;
}
