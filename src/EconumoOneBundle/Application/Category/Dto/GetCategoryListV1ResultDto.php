<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Dto;

use App\EconumoOneBundle\Application\Category\Dto\CategoryResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetCategoryListV1ResultDto
{
    /**
     * @var CategoryResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
