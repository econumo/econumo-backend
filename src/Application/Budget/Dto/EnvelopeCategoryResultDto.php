<?php

declare(strict_types=1);


namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "type", "icon", "isArchived", "envelopeId"}
 * )
 */
class EnvelopeCategoryResultDto
{
    /**
     * Id
     * @var string
     * @OA\Property(example="1b8559ac-4c77-47e4-a95c-1530a5274ab7")
     */
    public string $id;

    /**
     * Owner user id
     * @OA\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $ownerUserId;

    /**
     * Name
     * @var string
     * @OA\Property(example="Taxes")
     */
    public string $name;

    /**
     * Category type
     * @var string
     * @OA\Property(example="expense")
     */
    public string $type;

    /**
     * Icon
     * @var string
     * @OA\Property(example="local_offer")
     */
    public string $icon;

    /**
     * Is archived category?
     * @var int
     * @OA\Property(example="0")
     */
    public int $isArchived;

    /**
     * Envelope ID
     * @var string
     * @OA\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $envelopeId;
}