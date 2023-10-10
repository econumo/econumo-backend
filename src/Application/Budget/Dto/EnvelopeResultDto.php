<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name", "icon", "type", "currencyId", "folderId", "position"}
 * )
 */
class EnvelopeResultDto
{
    /**
     * Id
     * @var string
     * @OA\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $id;

    /**
     * Folder name
     * @var string
     * @OA\Property(example="Savings")
     */
    public string $name;

    /**
     * Icon
     * @var string
     * @OA\Property(example="home")
     */
    public string $icon;

    /**
     * Type
     * @var string
     * @OA\Property(example="expenses or income")
     */
    public string $type;

    /**
     * Currency Id
     * @var string
     * @OA\Property(example="77adad8a-9982-4e08-8fd7-5ef336c7a5c9")
     */
    public string $currencyId;

    /**
     * Folder Id
     * @var string|null
     * @OA\Property(example="2157e2b8-aa70-467b-ba0c-46ee7c29c0ec")
     */
    public ?string $folderId = null;

    /**
     * Position
     * @var int
     * @OA\Property(example="1")
     */
    public int $position;
}
