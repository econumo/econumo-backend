<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id"}
 * )
 */
class DeleteEnvelopeV1RequestDto
{
    /**
     * @OA\Property(example="f8ffe0ef-981a-41ab-9f53-8915a94f96ce")
     */
    public string $id;
}
