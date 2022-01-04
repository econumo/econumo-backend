<?php

declare(strict_types=1);

namespace App\Application\Category\Dto;

use App\Domain\Service\Dto\PositionDto;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @SWG\Definition(
 *     required={"changes"}
 * )
 */
class OrderCategoryListV1RequestDto
{
    /**
     * @var PositionDto[]
     * @SWG\Property(type="array", @SWG\Items(type="object", ref=@Model(type=\App\Domain\Service\Dto\PositionDto::class)))
     */
    public array $changes;
}
