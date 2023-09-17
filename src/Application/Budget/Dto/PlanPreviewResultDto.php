<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "ownerUserId", "name", "position", "isArchived", "createdAt", "updatedAt"}
 * )
 */
class PlanPreviewResultDto
{
    /**
     * @OA\Property(example="b14b4662-4ec6-42c1-ad8e-f2c99f289f43")
     */
    public string $id;

    /**
     * Owner user id
     * @OA\Property(example="f680553f-6b40-407d-a528-5123913be0aa")
     */
    public string $ownerUserId;

    /**
     * @OA\Property(example="Family budget")
     */
    public string $name;

    /**
     * @OA\Property(example="0")
     */
    public int $position;

    /**
     * Is archived plan?
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
}
