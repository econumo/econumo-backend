<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction\Dto;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"imported", "skipped", "errors"}
 * )
 */
class ImportTransactionListV1ResultDto
{
    /**
     * Number of successfully imported transactions
     * @OA\Property(example=10)
     */
    public int $imported = 0;

    /**
     * Number of skipped transactions
     * @OA\Property(example=2)
     */
    public int $skipped = 0;

    /**
     * Error messages for failed imports
     * @OA\Property(type="array", @OA\Items(type="string"), example={"Row 3: Invalid account name", "Row 5: Invalid date format"})
     */
    public array $errors = [];
}
