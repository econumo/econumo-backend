<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Account\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id"}
 * )
 */
class ShowFolderV1RequestDto
{
    /**
     * @OA\Property(example="1ad16d32-36af-496e-9867-3919436b8d86")
     */
    public string $id;
}
