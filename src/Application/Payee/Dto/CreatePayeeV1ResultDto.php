<?php

declare(strict_types=1);

namespace App\Application\Payee\Dto;

use App\Application\Payee\Dto\PayeeResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"item"}
 * )
 */
class CreatePayeeV1ResultDto
{
    /**
     * Payee
     * @OA\Property()
     */
    public PayeeResultDto $item;
}
