<?php

declare(strict_types=1);

namespace App\Application\Payee\Payee\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"id", "name"}
 * )
 */
class CreatePayeeV1RequestDto
{
    /**
     * @SWG\Property(example="123")
     */
    public string $id;

    /**
     * @SWG\Property(example="Amazon")
     */
    public string $name;

    /**
     * @SWG\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public ?string $accountId = null;
}
