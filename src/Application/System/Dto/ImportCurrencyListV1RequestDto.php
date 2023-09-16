<?php

declare(strict_types=1);

namespace App\Application\System\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class ImportCurrencyListV1RequestDto
{
    /**
     * @var string[]
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    public array $items = [];
}
