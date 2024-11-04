<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Payee\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name"}
 * )
 */
class UpdatePayeeV1RequestDto
{
    /**
     * @OA\Property(example="701ee173-7c7e-4f92-8af7-a27839c663e0")
     */
    public string $id;

    /**
     * @OA\Property(example="Amazon")
     */
    public string $name;
}
