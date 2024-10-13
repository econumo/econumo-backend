<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Category\Dto;

use App\EconumoOneBundle\Domain\Service\Dto\PositionDto;
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
     * @OA\Property(type="array", @OA\Items(type="object", ref=@Model(type=\App\EconumoOneBundle\Domain\Service\Dto\PositionDto::class)))
     */
    public array $changes = [];
}
