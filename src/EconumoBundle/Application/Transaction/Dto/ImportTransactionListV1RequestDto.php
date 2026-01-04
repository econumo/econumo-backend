<?php

declare(strict_types=1);

namespace App\EconumoBundle\Application\Transaction\Dto;

use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @OA\Schema(
 *     required={"file", "mapping"}
 * )
 */
class ImportTransactionListV1RequestDto
{
    /**
     * @OA\Property(type="string", format="binary", description="CSV file to import")
     */
    public ?UploadedFile $file = null;

    /**
     * @OA\Property(
     *     type="object",
     *     description="Field mapping configuration",
     *     example={"account":"Account Name","date":"Transaction Date","amount":"Amount","amountInflow":null,"amountOutflow":null,"category":"Category","description":"Description","payee":"Merchant","tag":null}
     * )
     */
    public array $mapping = [];
}
