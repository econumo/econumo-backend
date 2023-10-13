<?php

declare(strict_types=1);


namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "isArchived", "createdAt", "updatedAt", "envelopeId"}
 * )
 */
class EnvelopeTagResultDto
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
     * @OA\Property(example="Apple")
     */
    public string $name;

    /**
     * Is archived tag?
     * @var int
     * @OA\Property(example="0")
     */
    public int $isArchived;

    /**
     * Created at
     * @var string
     * @OA\Property(example="2021-01-01 12:15:00")
     */
    public string $createdAt;

    /**
     * Updated at
     * @var string
     * @OA\Property(example="2021-01-01 12:15:00")
     */
    public string $updatedAt;

    /**
     * Envelope ID
     * @var string
     * @OA\Property(example="a5e2eee2-56aa-43c6-a827-ca155683ea8d")
     */
    public string $envelopeId;
}