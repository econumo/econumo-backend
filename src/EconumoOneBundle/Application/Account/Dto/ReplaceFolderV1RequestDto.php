<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "replaceId"}
 * )
 */
class ReplaceFolderV1RequestDto
{
    /**
     * @OA\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $id;

    /**
     * @OA\Property(example="0aaa0450-564e-411e-8018-7003f6dbeb92")
     */
    public string $replaceId;
}
