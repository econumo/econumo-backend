<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"planId", "folderId", "type", "name", "icon", "currencyId", "categories", "tags"},
 * )
 */
class CreateEnvelopeV1RequestDto
{
    /**
     * @OA\Property(example="16c88ac2-b548-4446-9e27-51a28156b299")
     */
    public string $planId;

    /**
     * @OA\Property(example="2ec2df9d-240e-4355-bd8e-e0425fd72e1d")
     */
    public ?string $folderId;

    /**
     * @OA\Property(example="expense")
     */
    public string $type;

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
