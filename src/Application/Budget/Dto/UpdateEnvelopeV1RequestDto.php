<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name", "icon", "currencyId", "categories", "tags"},
 * )
 */
class UpdateEnvelopeV1RequestDto
{
    /**
     * @OA\Property(example="f8ffe0ef-981a-41ab-9f53-8915a94f96ce")
     */
    public string $id;

    /**
     * @OA\Property(example="My envelope")
     */
    public string $name;

    /**
     * @OA\Property(example="wallet")
     */
    public string $icon;

    /**
     * @OA\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * List of categories IDs
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    public array $categories = [];

    /**
     * List of tags IDs
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    public array $tags = [];
}
