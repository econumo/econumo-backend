<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Tag\Dto;

use App\EconumoOneBundle\Application\Tag\Dto\TagResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"items"}
 * )
 */
class GetTagListV1ResultDto
{
    /**
     * @var TagResultDto[]
     * @OA\Property()
     */
    public array $items = [];
}
