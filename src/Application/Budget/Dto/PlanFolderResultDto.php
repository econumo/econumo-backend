<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "name", "position"}
 * )
 */
class PlanFolderResultDto
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
     * Position
     * @var int
     * @OA\Property(example="1")
     */
    public int $position;
}
