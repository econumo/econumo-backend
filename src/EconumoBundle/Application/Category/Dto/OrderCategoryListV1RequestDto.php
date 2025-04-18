<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Category\Dto;

use App\EconumoBundle\Domain\Service\Dto\PositionDto;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @OA\Schema(
 *     required={"changes"}
 * )
 */
class OrderCategoryListV1RequestDto
{
    /**
     * @var PositionDto[]
     * @OA\Property(type="array", @OA\Items(type="object", ref=@Model(type=\App\EconumoBundle\Domain\Service\Dto\PositionDto::class)))
     */
    public array $changes = [];
}
