<?php

declare(strict_types=1);


namespace App\Application\Currency\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "code", "name", "symbol"}
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
     * Currency code
     * @var string
     * @SWG\Property(example="USD")
     */
    public string $code;

    /**
     * Currency
     * @var string
     * @SWG\Property(example="United States Dollar")
     */
    public string $name;

    /**
     * Currency symbol
     * @var string
     * @SWG\Property(example="$")
     */
    public string $symbol;
}
