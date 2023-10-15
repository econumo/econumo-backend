<?php

declare(strict_types=1);

namespace App\Application\Budget\Dto;

use App\Domain\Service\Dto\PositionDto;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @OA\Schema(
 *     required={"planId", "changes"}
 * )
 */
class OrderFolderListV1RequestDto
{
    /**
     * @OA\Property(example="16c88ac2-b548-4446-9e27-51a28156b299")
     */
    public string $planId;

    /**
     * @var PositionDto[]
     * @OA\Property(type="array", @OA\Items(type="object", ref=@Model(type=\App\Domain\Service\Dto\PositionDto::class)))
     */
    public array $changes = [];
}
