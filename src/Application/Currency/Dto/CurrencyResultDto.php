<?php

declare(strict_types=1);


namespace App\Application\Currency\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "alias", "sign"}
 * )
 */
class CurrencyResultDto
{
    /**
     * Id
     * @var string
     * @SWG\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $id;

    /**
     * Currency alias
     * @var string
     * @SWG\Property(example="RUB")
     */
    public string $alias;

    /**
     * Currency signature
     * @var string
     * @SWG\Property(example="$")
     */
    public string $sign;
}
