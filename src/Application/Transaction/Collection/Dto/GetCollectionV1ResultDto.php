<?php

declare(strict_types=1);

namespace App\Application\Transaction\Collection\Dto;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *     required={"items"}
 * )
 */
class GetCollectionV1ResultDto
{
    /**
     * @var TransactionResultDto[]
     * @SWG\Property()
     */
    public array $items = [];
}
