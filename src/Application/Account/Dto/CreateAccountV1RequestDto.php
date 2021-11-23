<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name", "currencyId", "balance"}
 * )
 */
class CreateAccountV1RequestDto
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
     * @SWG\Property(example="fe5d9269-b69c-4841-9c04-136225447eca")
     */
    public string $currencyId;

    /**
     * @SWG\Property(example="21007.64")
     */
    public float $balance = 0.0;

    /**
     * @SWG\Property(example="wallet")
     */
    public string $icon = '';
}
