<?php

declare(strict_types=1);

namespace App\Application\Account\Dto;

use App\Domain\Service\Dto\AccountPositionDto;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @SWG\Definition(
 *     required={"changes"}
 * )
 */
class OrderAccountListV1RequestDto
{
    /**
     * @var AccountPositionDto[]
     * @SWG\Property(type="array", @SWG\Items(type="object", ref=@Model(type=\App\Domain\Service\Dto\AccountPositionDto::class)))
     */
    public array $changes;
}
